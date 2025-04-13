
@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Checkout</h4>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('customer.checkout.process') }}">
        @csrf

        <!-- Rincian Pesanan -->
        <div class="mb-4">
            <h5>Rincian Pesanan</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $menuId => $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>Rp.{{ number_format($item['total_price'] / $item['quantity'], 0, ',', '.') }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>Rp.{{ number_format($item['total_price'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Alamat Pengiriman -->
        <div class="mb-3">
            <label for="address" class="form-label">Alamat Pengiriman</label>
            <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" required>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Metode Pembayaran -->
        <div class="mb-3">
            <label for="payment_method" class="form-label">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Kartu Kredit</option>
                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                <option value="cash_on_delivery" {{ old('payment_method') == 'cash_on_delivery' ? 'selected' : '' }}>Cash on Delivery</option>
            </select>
            @error('payment_method')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tombol Checkout -->
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success">Proses Checkout</button>
        </div>
    </form>
</div>
@endsection
