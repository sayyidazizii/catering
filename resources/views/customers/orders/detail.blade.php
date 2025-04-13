@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Pesanan #{{ $order->order_number }}</h4>
    </div>

    <div class="mb-4">
        <strong>Status:</strong> 
        <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'confirmed' ? 'success' : 'secondary') }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="mb-4">
        <strong>Tanggal Pesanan:</strong> {{ $order->order_date->format('d M Y') }}<br>
        <strong>Tanggal Pengiriman:</strong> {{ $order->delivery_date->format('d M Y') }}
    </div>

    <h5>Rincian Pesanan</h5>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->menu->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp.{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp.{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-between">
        <strong>Total Harga:</strong> 
        <strong>Rp.{{ number_format($order->total_price, 0, ',', '.') }}</strong>
    </div>
</div>
@endsection
