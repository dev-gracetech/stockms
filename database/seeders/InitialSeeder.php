<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\SystemSetting as StockSetting;
use App\Models\User;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\Stock;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockSetting::create([
            'company_name' => 'Your Company Name',
            'high_stock_threshold' => 1000,
            'low_stock_threshold' => 10,
            'expiry_alert_days' => 30,
            'default_stock_location' => 'Warehouse A',
            'notification_email' => 'admin@example.com',
            'currency' => 'USD',
        ]);

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin@123'),
        ]);

        Permission::create(['name' => 'user_manage']);
        Permission::create(['name' => 'stock_manage']);
        Permission::create(['name' => 'stock_request_issue']);
        Permission::create(['name' => 'system_setting_manage']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $user->assignRole('admin');
        
        Warehouse::create([
            'name' => 'Warehouse A',
            'location' => 'Main',
        ]);

        Branch::create([
            'name' => 'Branch 1',
            'location' => 'Branch',
        ]);
    }
}
