@csrf

<div class="mb-3">
    <label class="form-label">Nama Product</label>
    <input type="text"
           name="name"
           class="form-control"
           value="{{ old('name', $product->name ?? '') }}"
           required>
</div>

<div class="mb-3">
    <label class="form-label">Kategori</label>
    <input type="text"
           name="category"
           class="form-control"
           value="{{ old('category', $product->category ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="description"
              class="form-control"
              rows="4">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Harga</label>
        <input type="number"
               name="price"
               class="form-control"
               value="{{ old('price', $product->price ?? '') }}"
               min="0"
               required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Stock</label>
        <input type="number"
               name="stock"
               class="form-control"
               value="{{ old('stock', $product->stock ?? 0) }}"
               min="0"
               required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label" for="image">Gambar Produk</label>
    @if (isset($product) && $product->image_url)
        <div class="mb-2">
            <img src="{{ $product->image_url }}" alt="Gambar {{ $product->name }}" class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
        </div>
    @endif
    <input type="file"
           name="image"
           id="image"
           class="form-control @error('image') is-invalid @enderror"
           accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
    <div class="form-text">Format JPG, PNG, atau WebP. Maksimal 2 MB.</div>
    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-check mb-3">
    <input type="checkbox"
           name="is_active"
           value="1"
           class="form-check-input"
           id="is_active"
           {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>

    <label class="form-check-label" for="is_active">
        Product Aktif
    </label>
</div>

<button type="submit" class="btn btn-primary">
    {{ $buttonText ?? 'Simpan' }}
</button>

<a href="{{ route('products.index') }}" class="btn btn-secondary">
    Batal
</a>