<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Menu;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $location = $request->input('location');
        $merchant_name = $request->input('merchant_name');

        $menus = Menu::with('merchant')
            ->when($location, function ($query, $location) {
                return $query->whereHas('merchant', function ($query) use ($location) {
                    $query->where('location', $location);
                });
            })
            ->when($merchant_name, function ($query, $merchant_name) {
                return $query->whereHas('merchant', function ($query) use ($merchant_name) {
                    $query->where('company_name', 'like', '%' . $merchant_name . '%');
                });
            })
            ->get();
        return view('customers.orders.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'merchant_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::find($request->menu_id);
        $merchant = $menu->merchant;

        $totalPrice = $menu->price * $request->quantity;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'merchant_id' => $merchant->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'order_date' => now(),
                'delivery_date' => now()->addDays(1),  
                'status' => 'pending',
                'total_price' => $totalPrice,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'quantity' => $request->quantity,
                'price' => $menu->price,
                'subtotal' => $totalPrice,
            ]);

            DB::commit();

            return redirect()->route('customer.order.create')->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());
            return redirect()->route('customer.order.create')->with('error', 'Terjadi kesalahan saat membuat pesanan.');
        }
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::with('merchant')->findOrFail($request->menu_id);
        $quantity = $request->quantity;
        $totalPrice = $menu->price * $quantity;
        $subtotal = $totalPrice;

        $cart = session()->get('cart', []);
        $cartMerchantId = session()->get('cart_merchant_id');

        if (!empty($cart) && $cartMerchantId && $cartMerchantId != $menu->merchant_id) {
            return redirect()->route('customer.order.create')
                ->with('error', 'Keranjang hanya bisa berisi menu dari satu merchant.');
        }

        if (isset($cart[$menu->id])) {
            $cart[$menu->id]['quantity'] += $quantity;
            $cart[$menu->id]['total_price'] += $totalPrice;
        } else {
            $cart[$menu->id] = [
                'name' => $menu->name,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'price' => $menu->price,
                'subtotal' => $subtotal,
            ];
        }

        session()->put('cart', $cart);
        session()->put('cart_total', array_sum(array_column($cart, 'total_price')));
        session()->put('cart_merchant_id', $menu->merchant_id);

        return redirect()->route('customer.order.create');
    }

    public function removeFromCart($menuId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
            session()->put('cart', $cart);
            $cartTotal = array_sum(array_column($cart, 'total_price'));
            session()->put('cart_total', $cartTotal);
        }

        return redirect()->route('customer.order.create')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        $cartTotal = session()->get('cart_total', 0);

        if (empty($cart)) {
            return redirect()->route('customer.order.create')->with('error', 'Keranjang Anda kosong.');
        }

        return view('customers.orders.checkout', compact('cart', 'cartTotal'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'payment_method' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        $cartTotal = session()->get('cart_total', 0);
        $merchantId = session()->get('cart_merchant_id');

        if (empty($cart)) {
            return redirect()->route('customer.order.create')->with('error', 'Keranjang Anda kosong.');
        }

        if (!$merchantId) {
            return redirect()->route('customer.order.create')->with('error', 'Merchant tidak valid.');
        }

        $order = auth()->user()->orders()->create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'order_date' => now(),
            'delivery_date' => now()->addDays(1),
            'status' => 'pending',
            'total_price' => $cartTotal,
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'merchant_id' => $merchantId, 
        ]);

        foreach ($cart as $menuId => $item) {
            $order->orderItems()->create([
                'menu_id' => $menuId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['quantity'] * $item['price'],
            ]);
        }

        session()->forget('cart');
        session()->forget('cart_total');
        session()->forget('cart_merchant_id');

        return redirect()->route('customer.order.create')->with('success', 'Order Anda berhasil diproses.');
    }

    public function history()
    {
        $orders = Order::with('orderItems.menu', 'merchant')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customers.orders.history', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderItems.menu', 'merchant')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('customers.orders.show', compact('order'));
    }

    public function purchaseHistory()
    {
        $orders = auth()->user()->orders()->with('orderItems.menu')->latest()->get();

        return view('customers.orders.history', compact('orders'));
    }

    public function orderDetail($orderId)
    {
        $order = auth()->user()->orders()->with('orderItems.menu')->findOrFail($orderId);

        return view('customers.orders.detail', compact('order'));
    }

    public function invoice($id)
    {
        $order = Order::with(['orderItems.menu'])->findOrFail($id);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('customers.orders.invoice', compact('order'));
    }


}
