<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dashboard_uses_blue_branding(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('--brand-blue: #2563eb', false)
            ->assertSee('class="card dashboard-card', false)
            ->assertSee('class="text-primary fw-semibold">Selamat datang,', false)
            ->assertSee('class="btn btn-primary">Logout</button>', false);
    }

    public function test_product_edit_and_create_pages_render_inside_the_admin_layout(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $product = Product::create([
            'name' => 'Produk Contoh',
            'category' => 'Elektronik',
            'description' => 'Produk untuk pengujian form.',
            'price' => 100000,
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard | BetaMart Admin')
            ->assertSee('--admin-accent: #2563eb', false);

        $this->get(route('products.edit', $product))
            ->assertOk()
            ->assertSee('Edit Produk | BetaMart Admin')
            ->assertSeeInOrder(['ADMIN PANEL', 'Edit Product', 'Nama Product', '© 2026 BetaMart Admin'])
            ->assertSee('--admin-accent: #2563eb', false);

        $this->get(route('products.create'))
            ->assertOk()
            ->assertSee('Tambah Produk | BetaMart Admin')
            ->assertSeeInOrder(['ADMIN PANEL', 'Tambah Product', 'Nama Product', '© 2026 BetaMart Admin']);
    }

    public function test_admin_can_upload_and_replace_a_product_image(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post(route('products.store'), [
            'name' => 'Produk Contoh',
            'category' => 'Elektronik',
            'description' => 'Produk untuk pengujian gambar.',
            'price' => 100000,
            'stock' => 5,
            'image' => $this->fakePng('produk-lama.png'),
            'is_active' => '1',
        ])->assertRedirect(route('products.index'));

        $product = Product::query()->firstOrFail();
        $oldImage = $product->image;

        $this->assertNotNull($oldImage);
        Storage::disk('public')->assertExists($oldImage);
        $this->assertSame('/storage/'.$oldImage, $product->image_url);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('BetaMart Admin')
            ->assertSee('--admin-accent: #2563eb', false)
            ->assertSee('src="'.$product->image_url.'"', false);

        $this->post(route('products.update', $product), [
            '_method' => 'PUT',
            'name' => $product->name,
            'category' => $product->category,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'image' => $this->fakePng('produk-baru.png'),
            'is_active' => '1',
        ])->assertRedirect(route('products.index'));

        $product->refresh();
        $this->assertNotSame($oldImage, $product->image);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($product->image);

        $this->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'));

        Storage::disk('public')->assertMissing($product->image);
    }

    private function fakePng(string $name): UploadedFile
    {
        $contents = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADUlEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC'
        );

        return UploadedFile::fake()->createWithContent($name, $contents);
    }
}
