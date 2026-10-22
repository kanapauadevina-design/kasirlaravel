<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\EscPosReceiptPrinter;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CashierReceiptController extends Controller
{
    public function prepare(Request $request): JsonResponse
    {
        $receipt = $this->buildReceipt($request);

        return response()->json([
            'message' => 'Transaksi siap dicetak.',
            'receipt' => $receipt,
        ]);
    }

    public function __invoke(Request $request, EscPosReceiptPrinter $printer): JsonResponse
    {
        $receipt = $this->buildReceipt($request);

        try {
            $printer->print($receipt);
        } catch (Exception $exception) {
            report($exception);

            return response()->json([
                'message' => 'Struk gagal dikirim. Atur KASIR_PRINTER_NAME ke nama printer Windows yang dibagikan dan periksa koneksinya.',
            ], 503);
        }

        return response()->json([
            'message' => 'Pembayaran berhasil dan struk telah dikirim ke printer.',
            'transaction_number' => $receipt['transaction_number'],
        ]);
    }

    private function buildReceipt(Request $request): array
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.id' => ['required', 'integer', 'distinct'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:9999'],
            'customer' => ['nullable', 'string', 'max:120'],
            'payment_method' => ['required', 'in:Tunai,QRIS,Debit,Kredit,E-Wallet,Transfer'],
            'discount_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'tax' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'other_fee' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'payment' => ['required', 'numeric', 'min:0', 'max:999999999'],
        ]);

        $products = Product::query()
            ->where('is_active', true)
            ->whereIn('id', collect($validated['items'])->pluck('id'))
            ->get()
            ->keyBy('id');

        if ($products->count() !== count($validated['items'])) {
            throw ValidationException::withMessages([
                'items' => ['Satu atau lebih produk sudah tidak tersedia.'],
            ]);
        }

        $items = collect($validated['items'])->map(function (array $item) use ($products): array {
            $product = $products->get($item['id']);
            $unitPriceCents = (int) round((float) $product->price * 100);

            return [
                'name' => $product->name,
                'quantity' => (int) $item['qty'],
                'unit_price_cents' => $unitPriceCents,
                'line_total_cents' => $unitPriceCents * (int) $item['qty'],
            ];
        })->all();

        $subtotalCents = array_sum(array_column($items, 'line_total_cents'));
        $discountCents = (int) round($subtotalCents * (float) $validated['discount_percent'] / 100);
        $taxCents = (int) round((float) $validated['tax'] * 100);
        $otherFeeCents = (int) round((float) $validated['other_fee'] * 100);
        $totalCents = max(0, $subtotalCents - $discountCents + $taxCents + $otherFeeCents);
        $paymentCents = (int) round((float) $validated['payment'] * 100);

        if ($paymentCents < $totalCents) {
            throw ValidationException::withMessages([
                'payment' => ['Jumlah pembayaran kurang dari total transaksi.'],
            ]);
        }

        return [
            'transaction_number' => 'TRX-'.$request->user()->id.'-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
            'created_at' => now(),
            'cashier_name' => $request->user()->name,
            'customer' => $validated['customer'] ?: 'Umum',
            'payment_method' => $validated['payment_method'],
            'items' => $items,
            'subtotal_cents' => $subtotalCents,
            'discount_cents' => $discountCents,
            'tax_cents' => $taxCents,
            'other_fee_cents' => $otherFeeCents,
            'total_cents' => $totalCents,
            'payment_cents' => $paymentCents,
            'change_cents' => $paymentCents - $totalCents,
        ];
    }
}
