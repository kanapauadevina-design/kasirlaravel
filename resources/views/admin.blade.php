@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-heading', 'Ringkasan toko')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="text-secondary mb-1">Kelola operasional toko Anda dari satu tempat.</p>
            <h1 class="h3 fw-bold mb-0">Dashboard</h1>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            + Tambah produk
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">Total produk</div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <span class="display-6 fw-bold">{{ number_format($totalProducts) }}</span>
                        <span class="rounded-3 bg-primary-subtle text-primary px-3 py-2" aria-hidden="true">▣</span>
                    </div>
                    <div class="text-secondary small mt-2">Produk tersimpan di katalog</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">Produk aktif</div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <span class="display-6 fw-bold">{{ number_format($activeProducts) }}</span>
                        <span class="rounded-3 bg-success-subtle text-success px-3 py-2" aria-hidden="true">✓</span>
                    </div>
                    <div class="text-secondary small mt-2">Siap ditampilkan di toko</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">Stok menipis</div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <span class="display-6 fw-bold">{{ number_format($lowStockProducts) }}</span>
                        <span class="rounded-3 bg-warning-subtle text-warning-emphasis px-3 py-2" aria-hidden="true">!</span>
                    </div>
                    <div class="text-secondary small mt-2">Produk dengan stok 5 atau kurang</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary small">Total persediaan</div>
                    <div class="d-flex align-items-end justify-content-between mt-2">
                        <span class="display-6 fw-bold">{{ number_format($totalStock) }}</span>
                        <span class="rounded-3 bg-info-subtle text-info-emphasis px-3 py-2" aria-hidden="true">▤</span>
                    </div>
                    <div class="text-secondary small mt-2">Unit stok seluruh produk</div>
                </div>
            </div>
        </div>
    </div>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
            <div>
                <h2 class="h5 fw-bold mb-1">Produk terbaru</h2>
                <p class="text-secondary small mb-0">Pembaruan terkini pada katalog toko.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">Lihat semua produk</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Gambar</th>
                        <th class="ps-4">Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentProducts as $product)
                        <tr>
                            <td class="ps-4">
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}"
                                         alt="Gambar {{ $product->name }}"
                                         class="rounded"
                                         style="width: 48px; height: 48px; object-fit: cover;">
                                @else
                                    <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-secondary"
                                          style="width: 48px; height: 48px;"
                                          aria-label="Tidak ada gambar">—</span>
                                @endif
                            </td>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $product->name }}</div>
                                <div class="text-secondary small">#{{ $product->id }}</div>
                            </td>
                            <td>{{ $product->category ?: '—' }}</td>
                            <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="{{ $product->stock <= 5 ? 'text-danger fw-semibold' : '' }}">
                                    {{ number_format($product->stock) }}
                                </span>
                            </td>
                            <td>
                                @if ($product->is_active)
                                    <span class="badge rounded-pill text-bg-success">Aktif</span>
                                @else
                                    <span class="badge rounded-pill text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-light">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-5">
                                Belum ada produk. Tambahkan produk pertama untuk memulai katalog.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
