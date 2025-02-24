<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\Warehouse;
use App\Models\Branch;

class SystemSettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::first();
        $warehouses = Warehouse::all();
        $branches = Branch::all();
        $notifications = auth()->user()->notifications;
        return view('system-settings.index', compact('settings', 'warehouses', 'branches', 'notifications'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'high_stock_threshold' => 'required|integer|min:100',
            'low_stock_threshold' => 'required|integer|min:1',
            'expiry_alert_days' => 'required|integer|min:30',
            'default_stock_location' => 'nullable|string',
            'notification_email' => 'nullable|email',
            'currency' => 'required|string|size:3',
        ]);

        $settings = SystemSetting::firstOrNew();
        $settings->fill($request->all());
        $settings->save();

        return redirect()->route('system-settings.edit')->with('success', 'Settings updated successfully!');
    }
}
