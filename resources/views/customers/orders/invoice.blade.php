<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $order->order_number }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .invoice-box h2 { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>Invoice</h2>
        <p><strong>No. Pesanan:</strong> {{ $order->order_number }}</p>
        <p><strong>Tanggal:</strong> {{ $order->order_date->format('d M Y') }}</p>
        <p><strong>Pengiriman:</strong> {{ $order->delivery_date->format('d M Y') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Alamat:</strong> {{ $order->address }}</p>
        <p><strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>

        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
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

        <h4 class="text-end">Total: Rp.{{ number_format($order->total_price, 0, ',', '.') }}</h4>

        <div class="text-center mt-5">
            <button onclick="window.print()" class="btn btn-primary">🖨 Cetak</button>
        </div>
    </div>
</body>
</html>
