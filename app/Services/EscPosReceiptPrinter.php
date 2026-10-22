<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use RuntimeException;

class EscPosReceiptPrinter
{
    /**
     * @param array{
     *     transaction_number: string,
     *     created_at: Carbon,
     *     cashier_name: string,
     *     customer: string,
     *     payment_method: string,
     *     items: array<int, array{name: string, quantity: int, unit_price_cents: int, line_total_cents: int}>,
     *     subtotal_cents: int,
     *     discount_cents: int,
     *     tax_cents: int,
     *     other_fee_cents: int,
     *     total_cents: int,
     *     payment_cents: int,
     *     change_cents: int
     * } $receipt
     */
    public function print(array $receipt): void
    {
        $printerName = trim((string) config('escpos.printer_name'));

        if ($printerName === '') {
            throw new RuntimeException('Set KASIR_PRINTER_NAME di .env ke nama printer Windows yang dibagikan.');
        }

        $width = (int) config('escpos.columns', 42);

        if ($width < 24 || $width > 64) {
            throw new InvalidArgumentException('Lebar struk harus di antara 24 dan 64 kolom.');
        }

        $printer = new Printer(new WindowsPrintConnector($printerName));

        try {
            $printer->setEmphasis(true);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("VioraMart\n");
            $printer->setTextSize(1, 1);
            $printer->text("Struk Pembayaran\n");
            $printer->text($receipt['created_at']->format('d/m/Y H:i:s')."\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $this->separator($printer, $width);
            $printer->text('No: '.$this->cleanText($receipt['transaction_number'])."\n");
            $printer->text('Kasir: '.$this->cleanText($receipt['cashier_name'])."\n");
            $printer->text('Pelanggan: '.$this->cleanText($receipt['customer'])."\n");
            $printer->text('Metode: '.$this->cleanText($receipt['payment_method'])."\n");
            $this->separator($printer, $width);

            foreach ($receipt['items'] as $item) {
                $printer->text($this->cleanText($item['name'])."\n");
                $description = $item['quantity'].' x '.$this->formatRupiah($item['unit_price_cents']);
                $printer->text($this->columns($description, $this->formatRupiah($item['line_total_cents']), $width)."\n");
            }

            $this->separator($printer, $width);
            $this->printAmount($printer, 'Subtotal', $receipt['subtotal_cents'], $width);
            $this->printAmount($printer, 'Diskon', -$receipt['discount_cents'], $width);
            $this->printAmount($printer, 'Pajak', $receipt['tax_cents'], $width);
            $this->printAmount($printer, 'Biaya lain', $receipt['other_fee_cents'], $width);
            $printer->setTextSize(1, 2);
            $this->printAmount($printer, 'TOTAL', $receipt['total_cents'], $width);
            $printer->setTextSize(1, 1);
            $this->printAmount($printer, 'Bayar', $receipt['payment_cents'], $width);
            $this->printAmount($printer, 'Kembali', $receipt['change_cents'], $width);
            $this->separator($printer, $width);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima kasih telah berbelanja\n");
            $printer->barcode($receipt['transaction_number'], Printer::BARCODE_CODE39);
            $printer->text($this->cleanText($receipt['transaction_number'])."\n");
            $printer->feed(2);
            $printer->cut();
        } finally {
            $printer->close();
        }
    }

    private function separator(Printer $printer, int $width): void
    {
        $printer->text(str_repeat('-', $width)."\n");
    }

    private function printAmount(Printer $printer, string $label, int $amountCents, int $width): void
    {
        $amount = $amountCents < 0
            ? '-'.$this->formatRupiah(abs($amountCents))
            : $this->formatRupiah($amountCents);

        $printer->text($this->columns($label, $amount, $width)."\n");
    }

    private function columns(string $left, string $right, int $width): string
    {
        $left = $this->cleanText($left);
        $right = $this->cleanText($right);
        $available = max(1, $width - strlen($left) - strlen($right));

        return $left.str_repeat(' ', $available).$right;
    }

    private function formatRupiah(int $amountCents): string
    {
        $rupiah = intdiv($amountCents, 100);
        $fraction = $amountCents % 100;
        $formatted = 'Rp '.number_format($rupiah, 0, ',', '.');

        return $fraction === 0
            ? $formatted
            : $formatted.','.str_pad((string) $fraction, 2, '0', STR_PAD_LEFT);
    }

    private function cleanText(string $text): string
    {
        return trim(preg_replace('/[\x00-\x1F\x7F]/', ' ', $text) ?? '');
    }
}
