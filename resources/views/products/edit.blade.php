@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-heading', 'Edit produk')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h1 class="h3 mb-1">Edit Product</h1>
        <p class="text-muted mb-0">
            Ubah data product
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">

                @method('PUT')

                @include('products._form', [
                    'buttonText' => 'Update Product'
                ])

            </form>

        </div>
    </div>

</div>

@endsection
