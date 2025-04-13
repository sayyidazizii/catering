<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MerchantController extends Controller
{
    public function editProfile()
    {
        $merchant = Auth::user()->merchant;

        return view('merchant.edit', compact('merchant'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address'      => 'required|string|max:255',
            'contact'      => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'location'     => 'nullable|string|max:1000',
        ]);

        $merchant = Auth::user()->merchant;

        if (!$merchant) {
            return redirect()->back()->with('error', 'Merchant tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $merchant->update($request->only([
                'company_name', 'address', 'contact', 'description', 'location'
            ]));

            DB::commit();
            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            // Optional: log error
            Log::error('Update profile failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }

    public function order()
    {
        $merchant = auth()->user()->merchant;

        if (!$merchant) {
            return redirect()->route('home')->with('error', 'Akun ini bukan merchant.');
        }

        $orders = Order::where('merchant_id', $merchant->id)
                    ->with('user', 'orderItems.menu')
                    ->latest()
                    ->get();

        return view('merchant.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        if ($order->merchant_id != auth()->user()->merchant->id) {
            return redirect()->route('merchant.orders.list')->with('error', 'Anda tidak memiliki akses untuk mengubah status pesanan ini.');
        }
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed',
        ]);

        $order->status = $validated['status'];
        $order->save();

        return redirect()->route('merchant.orders.list')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function printInvoice($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->merchant_id != auth()->user()->merchant->id) {
            return redirect()->route('merchant.orders.list')->with('error', 'Anda tidak memiliki akses untuk mencetak invoice pesanan ini.');
        }

        return view('merchant.orders.invoice', compact('order'));
    }


}
