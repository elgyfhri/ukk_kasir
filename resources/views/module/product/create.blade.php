@extends('main')
@section('title', '| Product Create')

@section('content')

<div class="row">
    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @error('image')
        <div class="alert alert-danger">{{ $message }}</div>
         @enderror
      
        <!-- Nama Produk -->
        <div class="mb-3">
          <label for="name" class="form-label">Nama Produk</label>
          <input type="text" class="form-control border-secondary"  id="name" name="name" required value={{ old('name') }}>
        </div>
      
        <!-- Harga -->
        <div class="mb-3">
          <label for="price" class="form-label">Harga</label>
          <input type="number" class="form-control border-secondary"  id="price" name="price" required max="9999999999" 
          oninput="this.value = this.value.slice(0, 10)" value={{ old('price') }}>
        </div>
      
        <!-- Stok -->
        <div class="mb-3">
          <label for="stock" class="form-label">Stock</label>
          <input type="number" class="form-control border-secondary"  id="stock" name="stock" required max="9999999999" 
          oninput="this.value = this.value.slice(0, 10)" value="{{ old('stock') }}">
        </div>
      
        <!-- Gambar Produk -->
        <div class="mb-3">
          <label for="image" class="form-label">Gambar Produk</label>
          <input type="file" class="form-control border-secondary"  id="image" name="image" accept="image/*" required>
        </div>
      
        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>      
</div>

@endsection
