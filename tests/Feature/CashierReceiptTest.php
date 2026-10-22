<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Services\EscPosReceiptPrinter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CashierReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_page_uses_active_products_from_the_database(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        Product::query()->create([
            'name' => 'Kopi Aktif',
            'price' => 3500,
            'stock' => 10,
            'is_active' => true,
        ]);
        Product::query()->create([
            'name' => 'Produk Nonaktif',
            'price' => 5000,
            'stock' => 0,
            'is_active' => false,
        ]);

        $this->actingAs($cashier)
            ->get(route('kasir.dashboard'))
            ->assertOk()
            ->assertSee('Kopi Aktif')
            ->assertSee('id="productResults"', false)
            ->assertSee('onclick="addToCart('.Product::query()->where('name', 'Kopi Aktif')->value('id').')"', false)
            ->assertDontSee('Produk Nonaktif');
    }

    public function test_cashier_catalog_reflects_products_added_edited_and_deleted_by_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($admin)
            ->post(route('products.store'), [
                'name' => 'Teh Lama',
                'price' => 2500,
                'stock' => 10,
                'is_active' => '1',
            ])
            ->assertRedirect(route('products.index'));

        $product = Product::query()->firstOrFail();

        $this->actingAs($cashier)
            ->getJson(route('kasir.products'))
            ->assertOk()
            ->assertJsonPath('products.0.id', $product->id)
            ->assertJsonPath('products.0.name', 'Teh Lama')
            ->assertHeader('Cache-Control', 'no-store, private');

        $this->actingAs($admin)
            ->put(route('products.update', $product), [
                'name' => 'Teh Baru',
                'price' => 4000,
                'stock' => 8,
                'is_active' => '1',
            ])
            ->assertRedirect(route('products.index'));

        $this->actingAs($cashier)
            ->getJson(route('kasir.products'))
            ->assertOk()
            ->assertJsonPath('products.0.name', 'Teh Baru')
            ->assertJsonPath('products.0.price', '4000.00');

        $this->actingAs($admin)
            ->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'));

        $this->actingAs($cashier)
            ->getJson(route('kasir.products'))
            ->assertOk()
            ->assertExactJson(['products' => []]);
    }

    public function test_cashier_prints_a_receipt_using_database_product_prices(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $product = Product::query()->create([
            'name' => 'Kopi Viora',
            'price' => 3500,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->mock(EscPosReceiptPrinter::class, function (MockInterface $mock) use ($cashier): void {
            $mock->shouldReceive('print')
                ->once()
                ->with(Mockery::on(function (array $receipt) use ($cashier): bool {
                    return $receipt['items'][0]['name'] === 'Kopi Viora'
                        && $receipt['cashier_name'] === $cashier->name
                        && $receipt['items'][0]['unit_price_cents'] === 350000
                        && $receipt['subtotal_cents'] === 700000
                        && $receipt['discount_cents'] === 70000
                        && $receipt['total_cents'] === 705000
                        && $receipt['payment_cents'] === 1000000
                        && $receipt['change_cents'] === 295000;
                }));
        });

        $response = $this->actingAs($cashier)->postJson(route('kasir.receipt.print'), [
            'items' => [
                ['id' => $product->id, 'qty' => 2],
            ],
            'customer' => 'Umum',
            'payment_method' => 'Tunai',
            'discount_percent' => 10,
            'tax' => 500,
            'other_fee' => 250,
            'payment' => 10000,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('message', 'Pembayaran berhasil dan struk telah dikirim ke printer.');
    }

    public function test_cashier_prepares_browser_print_receipt_using_database_prices(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $product = Product::query()->create([
            'name' => 'Kopi Browser',
            'price' => 3500,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->mock(EscPosReceiptPrinter::class, function (MockInterface $mock): void {
            $mock->shouldNotReceive('print');
        });

        $this->actingAs($cashier)
            ->postJson(route('kasir.receipt.prepare'), [
                'items' => [
                    ['id' => $product->id, 'qty' => 2],
                ],
                'customer' => 'Umum',
                'payment_method' => 'Tunai',
                'discount_percent' => 0,
                'tax' => 0,
                'other_fee' => 0,
                'payment' => 7000,
            ])
            ->assertOk()
            ->assertJsonPath('receipt.items.0.unit_price_cents', 350000)
            ->assertJsonPath('receipt.total_cents', 700000)
            ->assertJsonPath('receipt.payment_cents', 700000)
            ->assertJsonPath('receipt.change_cents', 0);
    }

    public function test_cashier_cannot_print_when_payment_is_less_than_total(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $product = Product::query()->create([
            'name' => 'Kopi Viora',
            'price' => 3500,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->mock(EscPosReceiptPrinter::class, function (MockInterface $mock): void {
            $mock->shouldNotReceive('print');
        });

        $response = $this->actingAs($cashier)->postJson(route('kasir.receipt.print'), [
            'items' => [
                ['id' => $product->id, 'qty' => 1],
            ],
            'customer' => 'Umum',
            'payment_method' => 'Tunai',
            'discount_percent' => 0,
            'tax' => 0,
            'other_fee' => 0,
            'payment' => 1000,
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('payment');
    }

    public function test_printer_errors_are_reported_without_marking_the_receipt_as_printed(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $product = Product::query()->create([
            'name' => 'Kopi Viora',
            'price' => 3500,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->mock(EscPosReceiptPrinter::class, function (MockInterface $mock): void {
            $mock->shouldReceive('print')
                ->once()
                ->andThrow(new \RuntimeException('Printer tidak tersedia.'));
        });

        $response = $this->actingAs($cashier)->postJson(route('kasir.receipt.print'), [
            'items' => [
                ['id' => $product->id, 'qty' => 1],
            ],
            'customer' => 'Umum',
            'payment_method' => 'Tunai',
            'discount_percent' => 0,
            'tax' => 0,
            'other_fee' => 0,
            'payment' => 4000,
        ]);

        $response
            ->assertServiceUnavailable()
            ->assertJsonPath(
                'message',
                'Struk gagal dikirim. Atur KASIR_PRINTER_NAME ke nama printer Windows yang dibagikan dan periksa koneksinya.'
            );
    }
}
