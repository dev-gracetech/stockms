<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SystemSettingController extends Controller
{
    public function edit()
    {
        $settings = SystemSetting::first();
        return view('system-settings.edit', compact('settings'));
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
