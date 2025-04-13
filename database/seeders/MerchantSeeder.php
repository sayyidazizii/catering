<?php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchant = Merchant::create([
            'user_id' => 2,
            'company_name' => 'Merchant Company',
            'address' => '123 Merchant St.',
            'contact' => '+62123456789',
            'location' => 'Semarang',
            'description' => 'deskripsi Perusahaan Katering.',
        ]);
    }
}
