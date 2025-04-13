@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Invoice Pesanan #{{ $order->order_number }}</h4>

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
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->menu->name }}</td>
                <td>Rp.{{ number_format($item->price, 0, ',', '.') }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp.{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h5>Total: Rp.{{ number_format($order->total_price, 0, ',', '.') }}</h5>

    <button class="btn btn-primary" onclick="window.print()">Print Invoice</button>
</div>
@endsection
