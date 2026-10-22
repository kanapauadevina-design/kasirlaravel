<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierCatalogController;
use App\Http\Controllers\CashierReceiptController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [AuthController::class, 'verificationNotice'])
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');
});

Route::get('/admin', function () {
    return view('admin', [
        'totalProducts' => Product::count(),
        'activeProducts' => Product::where('is_active', true)->count(),
        'lowStockProducts' => Product::where('stock', '<=', 5)->count(),
        'totalStock' => Product::sum('stock'),
        'recentProducts' => Product::latest()->limit(8)->get(),
    ]);
})->middleware('admin')->name('admin.dashboard');

Route::get('/kasir', function () {
    return view('kasir', [
        'products' => Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price']),
    ]);
})->middleware('kasir')->name('kasir.dashboard');

Route::get('/kasir/products', CashierCatalogController::class)
    ->middleware('kasir')
    ->name('kasir.products');

Route::post('/kasir/receipt/prepare', [CashierReceiptController::class, 'prepare'])
    ->middleware('kasir')
    ->name('kasir.receipt.prepare');

Route::post('/kasir/receipt/print', CashierReceiptController::class)
    ->middleware('kasir')
    ->name('kasir.receipt.print');

Route::resource('products', ProductController::class)->middleware('admin');
Route::get('/transaction/history', function () {
    return response()->json([
        'message' => 'Riwayat transaksi berhasil diakses',
        'transactions' => []
    ]);
});
