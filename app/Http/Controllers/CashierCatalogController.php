<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class CashierCatalogController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()
            ->json([
                'products' => Product::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get(['id', 'name', 'price']),
            ])
            ->header('Cache-Control', 'no-store, private');
    }
}
