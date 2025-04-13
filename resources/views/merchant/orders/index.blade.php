@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Daftar Pesanan Masuk</h4>

    @if($orders->isEmpty())
        <div class="alert alert-info">Belum ada pesanan.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Pesanan</th>
                    <th>Customer</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'confirmed' ? 'success' : 'secondary') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>Rp.{{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        <!-- Update Status -->
                        <form action="{{ route('merchant.order.update', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>

                        <!-- Cetak Invoice -->
                        <a href="{{ route('merchant.order.invoice', $order->id) }}" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-printer"></i> Cetak Invoice
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
