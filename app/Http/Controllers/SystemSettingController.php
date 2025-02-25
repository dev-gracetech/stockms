<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function updateData(Request $request)
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

        return redirect()->route('system-settings.index')->with('success', 'Settings updated successfully!');
    }

    // Update the logo
    public function updateLogo(Request $request)
    {
        $request->validate([
            'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        $settings = SystemSetting::firstOrNew();

        // Delete old logo if it exists
        if ($settings->company_logo && Storage::exists($settings->company_logo)) {
            Storage::delete($settings->company_logo);
        }

        // Store the new photo
        $path = $request->file('company_logo')->store('/', 'public');
        $settings->company_logo = $path;
        $settings->save();

        return response()->json([
            'success' => true,
            'logo_url' => asset('storage/' . $path),
        ]);
    }

    // Remove the logo
    public function removeLogo(Request $request)
    {
        $settings = SystemSetting::firstOrNew();

        if ($settings->company_logo && Storage::exists($settings->company_logo)) {
            Storage::delete($settings->company_logo);
            $settings->company_logo = null;
            $settings->save();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
