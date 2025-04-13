@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Riwayat Pembelian</h4>
    </div>

    @if($orders->isEmpty())
        <div class="alert alert-info">Anda belum melakukan pembelian.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Tanggal Pesanan</th>
                        <th>Tanggal Pengiriman</th>
                        <th>Status</th>
                        <th>Total Harga</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->order_date->format('d M Y') }}</td>
                            <td>{{ $order->delivery_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'confirmed' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>Rp.{{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('customer.order.detail', $order->id) }}" class="btn btn-info btn-sm">Lihat Detail</a>
                                <a href="{{ route('customer.order.invoice', $order->id) }}" class="btn btn-secondary btn-sm" target="_blank">Cetak Invoice</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
