<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\StockTransfer;
use App\Models\Stock;
use App\Models\Branch;
use App\Models\SystemSetting as StockSettting;

class ReportController extends Controller
{
    // Show issued stocks to branches
    public function issuedStocks(Request $request)
    {
        $query = StockTransfer::with(['stock', 'fromBranch', 'toBranch'])
            ->where('status', 'approved');

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $issuedStocks = $query->get();

        return view('reports.issued_stocks', compact('issuedStocks'));
    }

    // Show branch stock details
    public function branchStock(Request $request)
    {
        $query = Branch::with('stocks');

        // Filter by branch
        if ($request->branch) {
            $query->where('id', $request->branch);
        }

        $branches = $query->get();

        return view('reports.branch_stock', compact('branches'));
    }

    // Show stock details with overstock, less stock, and expiry alerts
    public function stockDetails(Request $request)
    {
        // Fetch all stocks with their branch quantities
        $stocks = Stock::with('branches')->get();

        $settings = StockSetting::first();
        $overstockThreshold = $settings->high_stock_threshold;
        $lessStockThreshold = $settings->low_stock_threshold;
        $expiryAlertDays = $settings->expiry_alert_days;

        // Define thresholds
        // $overstockThreshold = 100; // Example: Overstock if total quantity > 100
        // $lessStockThreshold = 10;  // Example: Less stock if total quantity < 10
        // $expiryAlertDays = 30;     // Example: Highlight stocks expiring within 30 days

        // Calculate total quantity, overstock, less stock, and expiry alerts
        $stocks->each(function ($stock) use ($overstockThreshold, $lessStockThreshold, $expiryAlertDays) {
            // Calculate total quantity across all branches
            $stock->total_quantity = $stock->branches->sum('pivot.quantity');

            // Determine overstock and less stock
            $stock->is_overstock = $stock->total_quantity > $overstockThreshold;
            $stock->is_less_stock = $stock->total_quantity < $lessStockThreshold;

            // Determine if the stock is nearing expiry
            $stock->is_near_expiry = Carbon::parse($stock->expiry_date)->diffInDays(Carbon::now()) <= $expiryAlertDays;
        });

        // Filter by status
        if ($request->status) {
            $stocks = $stocks->filter(function ($stock) use ($request) {
                if ($request->status == 'overstock') {
                    return $stock->is_overstock;
                } elseif ($request->status == 'less_stock') {
                    return $stock->is_less_stock;
                } elseif ($request->status == 'near_expiry') {
                    return $stock->is_near_expiry;
                }
            });
        }

        return view('reports.stock_details', compact('stocks'));
    }

    // Show expiry coming stock details
    public function expiryComingStocks(Request $request)
    {
        // Define the number of days for expiry alert (default: 30 days)
        $expiryAlertDays = $request->days_remaining ?? 30;

        // Fetch stocks that are nearing expiry
        $stocks = Stock::where('expiry_date', '<=', Carbon::now()->addDays($expiryAlertDays))
            ->orderBy('expiry_date')
            ->get();

        //$now = Carbon::now();
        //$daysRemaining = Carbon::parse($stock->expiry_date)->diffInDays(Carbon::now());

        return view('reports.expiry_coming_stocks', compact('stocks', 'expiryAlertDays'));
    }
    
}