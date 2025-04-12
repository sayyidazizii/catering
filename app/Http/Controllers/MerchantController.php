<?php

namespace App\Http\Controllers;

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
        ]);

        $merchant = Auth::user()->merchant;

        if (!$merchant) {
            return redirect()->back()->with('error', 'Merchant tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $merchant->update($request->only([
                'company_name', 'address', 'contact', 'description'
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


}
