@extends('layouts.frontend')

@section('title', 'Home')

@section('content')

<div class="container py-5">
    <section class="home-hero text-center mb-5">
        <span class="badge rounded-pill text-bg-light border mb-3 px-3 py-2" style="color:#9d174d!important; border-color:#f3b4d0!important;">
            Belanja lebih mudah bersama kami
        </span>
        <h1 class="display-5 fw-bold mb-3">Selamat Datang di VioraMart</h1>
        <p class="lead mb-4">
            Temukan kebutuhan pilihan dengan pengalaman belanja yang nyaman, praktis, dan menyenangkan.
        </p>
        <a href="{{ route('login') }}" class="btn btn-pink btn-lg px-4">Masuk ke Akun</a>
    </section>

    <div class="text-center mb-4">
        <h2 class="h3 fw-bold" style="color:#9d174d;">Kenapa memilih kami?</h2>
        <p class="text-secondary mb-0">Kami siap menemani kebutuhan Anda setiap hari.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <article class="home-feature-card p-4">
                <div class="fs-1 mb-3" aria-hidden="true">🎓</div>
                <h2 class="h5 fw-bold">Pilihan Berkualitas</h2>
                <p class="text-secondary mb-0">Produk pilihan yang mendukung kebutuhan belajar dan aktivitas harian.</p>
            </article>
        </div>

        <div class="col-md-4">
            <article class="home-feature-card p-4">
                <div class="fs-1 mb-3" aria-hidden="true">💻</div>
                <h2 class="h5 fw-bold">Belanja Praktis</h2>
                <p class="text-secondary mb-0">Jelajahi kebutuhan teknologi dan berbagai produk dengan mudah.</p>
            </article>
        </div>

        <div class="col-md-4">
            <article class="home-feature-card p-4">
                <div class="fs-1 mb-3" aria-hidden="true">🚀</div>
                <h2 class="h5 fw-bold">Mendukung Aktivitas</h2>
                <p class="text-secondary mb-0">Temukan perlengkapan yang membantu Anda melangkah lebih percaya diri.</p>
            </article>
        </div>
    </div>
</div>
