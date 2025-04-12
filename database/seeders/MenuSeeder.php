<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Merchant;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::whereHas('roles', function ($query) {
            $query->where('name', 'merchant');
        })->first();

        $merchant = Merchant::where('user_id', $user->id)->first();

        if ($merchant) {
            $merchant->menus()->createMany([
                [
                    'name' => 'Nasi Goreng',
                    'description' => 'Nasi goreng spesial dengan telur dan ayam.',
                    'price' => 25000,
                    'photo_path' => 'nasi_goreng.jpg',
                ],
                [
                    'name' => 'Sate Ayam',
                    'description' => 'Sate ayam dengan bumbu kacang.',
                    'price' => 30000,
                    'photo_path' => 'sate_ayam.jpg',
                ],
            ]);
        }
    }
}
