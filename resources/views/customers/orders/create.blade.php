@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Cari Menu Katering</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif              
    <!-- Form Pencarian -->
    <form method="GET" action="{{ route('customer.order.create') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="merchant_name" class="form-label">Nama Merchant</label>
                <input type="text" name="merchant_name" class="form-control" placeholder="Cari nama merchant..." value="{{ request('merchant_name') }}">
            </div>

            <div class="col-md-4">
                <label for="location" class="form-label">Lokasi</label>
                <select name="location" class="form-select">
                    <option value="">Pilih Lokasi</option>
                    @foreach(config('location') as $location)
                        <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                            {{ $location }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </div>
    </form>

    <div class="row">
        <!-- Kolom Kiri: Menampilkan Menu -->
        <div class="col-md-8">
            @if($menus->isEmpty())
                <div class="alert alert-info">Tidak ada menu yang ditemukan.</div>
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
                                    <p class="card-text">{{ $menu->merchant->company_name }}</p>
                                    <p class="card-text">{{ $menu->merchant->location }}</p>

                                    <!-- Input Quantity dan Tombol Beli -->
                                    <form action="{{ route('customer.order.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        <input type="hidden" name="merchant_id" value="{{ $menu->merchant->id }}">

                                        <!-- Input Quantity -->
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Jumlah</label>
                                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                                        </div>

                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-cart-plus"></i> Beli
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Kolom Kanan: Tabel Keranjang -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Keranjang Belanja</h5>
                </div>
                <div class="card-body">
                    @if(session('cart') && count(session('cart')) > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(session('cart') as $menuId => $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>Rp.{{ number_format($item['total_price'], 0, ',', '.') }}</td>
                                        <td>
                                            <!-- Form Hapus Item -->
                                            <form action="{{ route('customer.order.remove', $menuId) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between">
                            <strong>Total: </strong>
                            <strong>Rp.{{ number_format(session('cart_total'), 0, ',', '.') }}</strong>
                        </div>

                        <a href="{{ route('customer.order.checkout') }}" class="btn btn-primary w-100 mt-3">Checkout</a>
                    @else
                        <p class="text-muted">Keranjang Anda kosong.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
