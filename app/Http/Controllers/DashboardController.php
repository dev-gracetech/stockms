<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\Branch;
use App\Models\StockMovement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Show the dashboard
    public function index()
    {
        // Fetch data for charts
        $stocks = Stock::all();

        // Get the authenticated user
        $user = auth()->user();
        if ($user->hasRole('admin') or $user->hasRole('warehouse_manager'))
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
            $distinctStockIds = DB::table('stock_movements')
            ->select('stock_id')
            ->whereIn('to_branch_id', $branchIds)
            ->distinct()
            ->pluck('stock_id');

            $stocks = Stock::whereIn('id',$distinctStockIds)->get();
        }

        // Calculate total quantity per branch
        $branchQuantities = [];

        foreach ($branches as $branch) {
            $totalQuantity = StockMovement::where('to_branch_id', $branch->id)
                ->sum('quantity');
            $branchQuantities[] = [
                'branch_name' => $branch->name,
                'total_quantity' => $totalQuantity,
            ];
        }

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
