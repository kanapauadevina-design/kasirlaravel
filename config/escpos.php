<?php

return [
    'printer_name' => env('KASIR_PRINTER_NAME'),
    'columns' => (int) env('KASIR_RECEIPT_COLUMNS', 32),
];
