@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Edit Menu</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('merchant.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nama Menu</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $menu->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $menu->price) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $menu->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Foto Menu</label>
            <input type="file" name="photo" class="form-control">
            @if($menu->photo_path)
                <small class="text-muted">Foto Lama:</small><br>
                <img src="{{ asset('storage/' . $menu->photo_path) }}" alt="Menu Photo" style="width: 100px; height: 100px; object-fit: cover;">
            @endif
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('merchant.menu.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
