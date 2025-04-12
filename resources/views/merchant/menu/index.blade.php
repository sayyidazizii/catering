@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Menu Saya</h4>
        <a href="{{ route('merchant.menu.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Tambah Menu
        </a>
    </div>

    <form method="GET" action="{{ route('merchant.menu.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Cari menu..." value="{{ $search }}">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i> Cari
            </button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($menus->isEmpty())
        <div class="alert alert-info">Belum ada menu yang ditambahkan.</div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            @foreach($menus as $menu)
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            @if($menu->photo_path)
                                <img src="{{ asset('storage/' . $menu->photo_path) }}" class="card-img-top mb-3" style="object-fit: cover; height: 180px;">
                            @endif
                            <h5 class="card-title">{{ $menu->name }}</h5>
                            <p class="card-text text-muted">Rp.{{ number_format($menu->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                            <a href="{{ route('merchant.menu.edit', $menu->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('merchant.menu.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @endif
</div>
@endsection
