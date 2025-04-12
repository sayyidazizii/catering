<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;


class MenuController extends Controller
{
    public function index(Request $request)
    {
        $merchant = Auth::user()->merchant;
        if (!$merchant) {
            return redirect()->back()->with('error', 'Merchant tidak ditemukan.');
        }
        $search = $request->input('search');

        $menus = $merchant->menus()
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->get();

        return view('merchant.menu.index', compact('menus', 'search'));
    }

    public function create()
    {
        return view('merchant.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'description' => 'nullable|string|max:1000',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $merchant = Auth::user()->merchant;

            if (!$merchant) {
                return redirect()->back()->with('error', 'Merchant tidak ditemukan.');
            }

            if ($request->file('photo')->getSize() > 2048000) {
                return redirect()->back()->with('error', 'Ukuran foto terlalu besar.');
            }

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('menus', 'public');
            }

            $menu = $merchant->menus()->create([
                'name'        => $request->name,
                'price'       => $request->price,
                'description' => $request->description,
                'photo_path'  => $photoPath,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating menu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan menu.');
        }

        return redirect()->route('merchant.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $menu = Menu::where('id',$id)->first();
        return view('merchant.menu.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'description' => 'nullable|string|max:1000',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto
        ]);

        $menu = Menu::where('id', $id)->first();

        if ($request->hasFile('photo')) {
            if ($menu->photo_path && file_exists(storage_path('app/public/' . $menu->photo_path))) {
                unlink(storage_path('app/public/' . $menu->photo_path));
            }
            $photoPath = $request->file('photo')->store('menus', 'public');
        } else {
            $photoPath = $menu->photo_path;
        }
        $menu->update([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'photo_path'  => $photoPath,
        ]);

        return redirect()->route('merchant.menu.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $menu = Menu::where('id', $id)->first();
        if (!$menu) {
            return redirect()->route('merchant.menu.index')->with('error', 'Menu tidak ditemukan.');
        }
        if ($menu->photo_path) {
            Storage::disk('public')->delete($menu->photo_path);
        }
        DB::beginTransaction();
        try {
            $menu->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Hapus menu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus menu.');
        }

        return redirect()->route('merchant.menu.index')->with('success', 'Menu berhasil dihapus.');
    }


}

