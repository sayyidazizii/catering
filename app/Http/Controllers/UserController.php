<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->back()->with('error', 'User tidak ditemukan.');
            }

            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update user profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
