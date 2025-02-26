<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\Branch;
use App\Models\StockMovement;
use App\Models\SystemSetting;
use App\Models\BranchInventory;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Show the dashboard
    public function index()
    {
        // Fetch data for charts
        $stocks = Stock::all();
        $settings = SystemSetting::first();

        // Get the authenticated user
        $user = auth()->user();
        if ($user->hasRole('admin') or $user->hasRole('warehouse manager'))
        {
            $branchIds = Branch::all()->pluck('id');
            $branchNames = Branch::all()->pluck('name');
            $branches = Branch::all();
            $stocks = Stock::all();
        }
        else
        {
            $branchIds = $user->branches->pluck('id');
            $branchNames = $user->branches->pluck('name');
            $branches = $user->branches;
            $distinctStockIds = BranchInventory::all()->pluck('stock_id');
            /* $distinctStockIds = DB::table('stock_movements')
            ->select('stock_id')
            ->whereIn('to_branch_id', $branchIds)
            ->distinct()
            ->pluck('stock_id'); */

            $stocks = Stock::whereIn('id',$distinctStockIds)->get();
        }

        // Calculate total quantity per branch
        $branchQuantities = [];

        /* foreach ($branches as $branch) {
            $totalQuantity = StockMovement::where('to_branch_id', $branch->id)
                ->sum('quantity');
            $branchQuantities[] = [
                'branch_name' => $branch->name,
                'total_quantity' => $totalQuantity,
            ];
        } */

        foreach ($branches as $branch) {
            $totalQuantity = BranchInventory::where('branch_id', $branch->id)
                ->sum('quantity');
            $branchQuantities[] = [
                'branch_name' => $branch->name,
                'total_quantity' => $totalQuantity,
            ];
        }

        $expiryStocks = $stocks->filter(function ($stock) {
            return Carbon::parse($stock->expiry_date)->diffInDays(Carbon::now()) <= SystemSetting::first()->expiry_alert_days;
        });

        $overstockThreshold = $settings->high_stock_threshold;
        $lessStockThreshold = $settings->low_stock_threshold;

        $overstockCount = $stocks->filter(function ($stock) use ($overstockThreshold) {
            return $stock->quantity > $overstockThreshold;
        })->count();

        $lessStockCount = $stocks->filter(function ($stock) use ($lessStockThreshold) {
            return $stock->quantity < $lessStockThreshold;
        })->count();

        $notifications = auth()->user()->notifications;
        return view('dashboard.index', compact('stocks',
            'branchNames',
            'branchQuantities',
            'expiryStocks',
            'overstockCount',
            'lessStockCount',
            'notifications'
        ));
    }
}
