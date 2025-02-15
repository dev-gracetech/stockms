<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Branch;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Show the dashboard
    public function index()
    {
        // Fetch data for charts
        $branches = Branch::with('stocks')->get();
        $stocks = Stock::all();

        // Prepare data for charts
        $branchNames = $branches->pluck('name');
        $totalStockByBranch = $branches->map(function ($branch) {
            return $branch->stocks->sum('pivot.quantity');
        });

        $expiryStocks = $stocks->filter(function ($stock) {
            return Carbon::parse($stock->expiry_date)->diffInDays(Carbon::now()) <= 30;
        });

        $overstockThreshold = 100;
        $lessStockThreshold = 10;

        $overstockCount = $stocks->filter(function ($stock) use ($overstockThreshold) {
            return $stock->quantity > $overstockThreshold;
        })->count();

        $lessStockCount = $stocks->filter(function ($stock) use ($lessStockThreshold) {
            return $stock->quantity < $lessStockThreshold;
        })->count();

        return view('dashboard.index', compact('stocks',
            'branchNames',
            'totalStockByBranch',
            'expiryStocks',
            'overstockCount',
            'lessStockCount'
        ));
    }
}
