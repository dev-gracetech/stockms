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
            'name' => 'superadmin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin@123'),
        ]);

        Permission::create(['name' => 'user_manage']);
        Permission::create(['name' => 'role_manage']);
        Permission::create(['name' => 'permission_manage']);
        Permission::create(['name' => 'report_manage']);
        Permission::create(['name' => 'stock_manage']);
        Permission::create(['name' => 'stock_request']);
        Permission::create(['name' => 'stock_request_actions']);
        Permission::create(['name' => 'stock_request_issue']);
        Permission::create(['name' => 'branch_menu_access']);
        Permission::create(['name' => 'branch_manage']);
        Permission::create(['name' => 'warehouse_menu_access']);
        Permission::create(['name' => 'warehouse_manage']);
        Permission::create(['name' => 'stock_transfer']);
        Permission::create(['name' => 'system_setting_manage']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $user->assignRole('admin');

        $role = Role::create(['name' => 'warehouse manager']);
        $role->givePermissionTo(['user_manage','report_manage','stock_manage','stock_request_issue',
        'branch_menu_access','branch_manage','warehouse_menu_access','system_setting_manage','stock_request_actions']);
        
        $role = Role::create(['name' => 'branch user']);
        $role->givePermissionTo(['report_manage','branch_menu_access','stock_request']);
        
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
