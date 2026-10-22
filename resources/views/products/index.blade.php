@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Produk</h1>
            <p class="text-muted mb-0">
                Daftar produk
            </p>
        </div>

        <a href="{{ route('products.create') }}" class="btn btn-primary">
            Tambah Product
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <strong>Data Product</strong>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Product</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($products as $product)

                        <tr>
                            <td>
                                {{ $products->firstItem() + $loop->index }}
                            </td>

                            <td>
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}"
                                         alt="Gambar {{ $product->name }}"
                                         class="rounded"
                                         style="width: 56px; height: 56px; object-fit: cover;">
                                @else
                                    <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-secondary"
                                          style="width: 56px; height: 56px;"
                                          aria-label="Tidak ada gambar">—</span>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $product->name }}</strong>

                                @if($product->description)
                                    <br>
                                    <small class="text-muted">
                                        {{ Str::limit($product->description, 50) }}
                                    </small>
                                @endif
                            </td>

                            <td>
                                {{ $product->category ?? '-' }}
                            </td>

                            <td>
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $product->stock }}
                            </td>

                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('products.show', $product) }}"
                                   class="btn btn-sm btn-info text-white">
                                    Detail
                                </a>

                                <a href="{{ route('products.edit', $product) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('products.destroy', $product) }}"
                                      method="POST"
                                      style="display:inline;"
                                      onsubmit="return confirm('Yakin ingin menghapus product ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>

                                </form>
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="8" class="text-center py-4">
                                Belum ada data product.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $products->links() }}
            </div>

        </div>
    </div>

</div>

@endsection