<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemSetting as StockSetting;

class SystemSettingSeeder extends Seeder
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
    }
}
