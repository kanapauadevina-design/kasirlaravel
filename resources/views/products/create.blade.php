@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-heading', 'Tambah produk')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h1 class="h3 mb-1">Tambah Product</h1>
        <p class="text-muted mb-0">
            Tambahkan product baru
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @include('products._form', [
                    'buttonText' => 'Simpan Product'
                ])
            </form>

        </div>
    </div>

</div>

@endsection
