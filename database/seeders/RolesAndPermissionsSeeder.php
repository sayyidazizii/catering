<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $merchantRole = Role::firstOrCreate(['name' => 'merchant']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        $createMenuPermission = Permission::firstOrCreate(['name' => 'menu.create']);
        $editMenuPermission   = Permission::firstOrCreate(['name' => 'menu.edit']);
        $deleteMenuPermission = Permission::firstOrCreate(['name' => 'menu.delete']);

        $adminRole->syncPermissions([$createMenuPermission, $editMenuPermission, $deleteMenuPermission]);
        $merchantRole->syncPermissions([$createMenuPermission, $editMenuPermission, $deleteMenuPermission]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'admin', 'password' => bcrypt('12345678')]
        );
        $merchant = User::updateOrCreate(
            ['email' => 'merchant@gmail.com'],
            ['name' => 'merchant', 'password' => bcrypt('12345678')]
        );
        $customer = User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            ['name' => 'customer', 'password' => bcrypt('12345678')]
        );

        $admin->syncRoles(['admin']);
        $merchant->syncRoles(['merchant']);
        $customer->syncRoles(['customer']);
    }

}
