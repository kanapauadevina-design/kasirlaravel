<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
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
        'totalProducts' => \App\Models\Product::count(),
        'activeProducts' => \App\Models\Product::where('is_active', true)->count(),
        'lowStockProducts' => \App\Models\Product::where('stock', '<=', 5)->count(),
        'totalStock' => \App\Models\Product::sum('stock'),
        'recentProducts' => \App\Models\Product::latest()->limit(8)->get(),
    ]);
})->middleware('admin')->name('admin.dashboard');

Route::get('/kasir', function () {
    return view('kasir');
})->middleware('kasir')->name('kasir.dashboard');

Route::resource('products', ProductController::class)->middleware('admin');