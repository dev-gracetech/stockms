<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\StockTransfer;
use App\Models\StockMovement;
use App\Models\BranchInventory;
use App\Models\Replenishment;
use App\Models\Stock;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\Disposal;
use App\Models\SystemSetting;

class ReportController extends Controller
{
    // Show issued stocks to branches
    public function issuedStocks(Request $request)
    {
        $query = StockMovement::query();

        // Filter by branch
        if ($request->branch_id) {
            $query->where('to_branch_id', $request->branch_id);
        }

        // Filter by product
        if ($request->product_name) {
            $query->whereHas('stock', function ($q) use ($request) {
                $q->where('name', $request->product_name);
            });
        }

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        // Fetch the filtered stock movements
        $stockMovements = $query->with(['stock', 'toBranch'])->where('movement_type','issue')->get();

        //$totalSales = sum($stockMovement->quantity * $stockMovement->stock->selling_price);

        return view('reports.issued_stocks', [
            'branches' => Branch::all()->sortBy('name'),
            'products' => Stock::distinct('name')->pluck('name'),
            'stockMovements' => $stockMovements->sortByDesc('created_at'),
            'totalBuyingPrice' => $stockMovements->sum(function ($stockMovement) {
                if ($stockMovement->stock === null) {
                    return 0;
                }
                return $stockMovement->quantity * $stockMovement->stock->price;
            }),
            'totalSales' => $stockMovements->sum(function ($stockMovement) {
                if ($stockMovement->stock === null) {
                    return 0;
                }
                return $stockMovement->quantity * $stockMovement->stock->selling_price;
            }),
            'filters' => $request->all(),
        ]);
    }

    // Show branch stock details
    public function branchStock(Request $request)
    {
        $query = BranchInventory::query();

        // Filter by branch
        if ($request->branch) {
            $query->where('branch_id', $request->branch);
        }

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereHas('stock', function ($q) use ($request) {
                $q->whereBetween('expiry_date', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay(),
                ]);
            });
        }
        //     $query->whereBetween('expiry_date', [
        //         Carbon::parse($request->start_date)->startOfDay(),
        //         Carbon::parse($request->end_date)->endOfDay(),
        //     ]);
        // }

        $results = $query->get()->sortBy('branch_id');
        $branches = Branch::all()->sortBy('name');
        $filters = $request->all();
        
        //$notifications = auth()->user()->notifications;
        //return view('reports.branch_stock', compact('branches','results','filters'));
        return view('reports.branch_stock', [
            'branches' => $branches,
            'results' => $results,
            'filters' => $filters,
            'totalBuyingPrice' => $results->sum(function ($result) {
                if ($result->stock === null) {
                    return 0;
                }
                return $result->quantity * $result->stock->price;
            }),
            'totalSales' => $results->sum(function ($result) {
                if ($result->stock === null) {
                    return 0;
                }
                return $result->quantity * $result->stock->selling_price;
            }),
        ]);
    }

    // Show stock details with overstock, less stock, and expiry alerts
    public function stockDetails(Request $request)
    {
        // Fetch all stocks with their branch quantities
        $stocks = Stock::all();
        //$stocks = BranchInventory::with('stocks')->get();
        if(auth()->user()->hasrole('branch user'))
        {
            $branch =  $branch = auth()->user()->branches->pluck('id');
            $stock_ids = BranchInventory::where('branch_id', $branch)->pluck('stock_id');
            $stocks = Stock::whereIn('id', $stock_ids)->get();
        }

        $settings = SystemSetting::first();
        $overstockThreshold = $settings->high_stock_threshold;
        $lessStockThreshold = $settings->low_stock_threshold;
        $expiryAlertDays = $settings->expiry_alert_days;

        //$stocks = $query->with(['stockMovements'])->get();
        // Calculate total quantity, overstock, less stock, and expiry alerts
        $stocks->each(function ($stock) use ($overstockThreshold, $lessStockThreshold, $expiryAlertDays) {
            // Calculate total quantity across all branches
            //$stock->total_quantity = $stock->branches->sum('pivot.quantity');
            if(!auth()->user()->hasrole('branch user'))
            {
                $branches = BranchInventory::where('stock_id', $stock->id)->pluck('branch_id');
                $stock->branch = Branch::whereIn('id', $branches)->get();
            }
            else
            {
                $branch = auth()->user()->branches->pluck('id');
                $stock_branch = BranchInventory::where('branch_id', $branch)->where('stock_id', $stock->id)->first();
                $stock->quantity = $stock_branch->quantity;
            }
            //$stock->total_quantity = $stock->branch->sum('pivot.quantity');
            // Determine overstock and less stock
            $stock->is_overstock = $stock->quantity > $overstockThreshold;
            //$stock->is_less_stock = $stock->quantity < $lessStockThreshold;
            $stock->is_less_stock = $stock->quantity < $stock->minimum_threshold;

            // Determine if the stock is nearing expiry or expired
            $today = now()->startOfDay();
            $expiryDate = \Carbon\Carbon::parse($stock->expiry_date)->startOfDay();


            if ($expiryDate->diffInDays($today) <= $expiryAlertDays) {
                $stock->is_near_expiry = true;
                if ($expiryDate->isPast()) {
                    $stock->is_expired = true;
                    $stock->is_near_expiry = false;
                } else {
                    $stock->is_expired = false;
                }
            } else { 
                $stock->is_near_expiry = false;
            }

            $stock->no_expiry = false;
            if(($stock->expiry_date=='') || ($stock->expiry_date==null))
            {
                $stock->no_expiry = true;
                $stock->is_near_expiry = false;
                $stock->is_expired = false;
            }
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
                } elseif ($request->status == 'expired') {
                    return $stock->is_expired;
                }
            });
        }

        //$notifications = auth()->user()->notifications;
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

        $notifications = auth()->user()->notifications;
        return view('reports.expiry_coming_stocks', compact('stocks', 'expiryAlertDays', 'notifications'));
    }
    
    public function stockTracking(Request $request)
    {
        $query = Replenishment::query();

        // Filter by product
        if ($request->product_name) {
            $query->whereHas('stock', function ($q) use ($request) {
                $q->where('name', $request->product_name);
            });
        }

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        $results = $query->get();
        $filters = $request->all();
        $products = Stock::distinct('name')->pluck('name');

        return view('reports.track_stock', compact('results','filters','products'));
    }

    public function currentStocks(Request $request)
    {
        //$query = Stock::query();

        $query = DB::table('stock_warehouse as sw')
            ->join('stocks as s', 'sw.stock_id', '=', 's.id')
            ->join('warehouses as w', 'sw.warehouse_id', '=', 'w.id')
            ->select(
                's.name as stock', 's.batch as batch', 's.price as price', 's.selling_price as selling_price',
                'w.name as warehouse',
                DB::raw('SUM(sw.quantity) as total_quantity')
            )
            ->groupBy('s.name','s.batch','s.price','s.selling_price', 'w.name');

        // Filter by warehouse
        if ($request->warehouse_id) {
            $query->where('w.id', $request->warehouse_id);
        }

        //$totalQuantity = DB::table('stock_warehouse')->sum('quantity');
        // Filter by product
        if ($request->product_name) {
            $query->where('s.name', $request->product_name);
            //$totalQuantity = DB::table('stock_warehouse')->sum('quantity')->
        }

        // Filter by date range
        // if ($request->start_date && $request->end_date) {
        //     $query->whereBetween('created_at', [
        //         Carbon::parse($request->start_date)->startOfDay(),
        //         Carbon::parse($request->end_date)->endOfDay(),
        //     ]);
        // }

        $results = $query->get();
        $filters = $request->all();
        $products = Stock::distinct('name')->pluck('name');


        return view('reports.current_stock', [
            'warehouses' => Warehouse::all()->sortBy('name'),
            'results' => $results,
            'filters' => $filters,
            'products' => $products,
            'totalQuantity' => $results->sum('total_quantity'),
            //'totalBuyingPrice' => 0,
            //'totalSales' => 0,
            'totalBuyingPrice' => $results->sum(function ($result) {
                return $result->price * $result->total_quantity;
            }),
            'totalSales' => $results->sum(function ($result) {
                return $result->selling_price * $result->total_quantity;
            }),
        ]);
    }

    public function disposedStocks(Request $request)
    {
        $stocks = Disposal::query();

        // Filter by product
        if ($request->product_name) {
            $stocks->whereHas('stock', function ($q) use ($request) {
                $q->where('name', $request->product_name);
            });
        }

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $stocks->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        $results = $stocks->get();
        $filters = $request->all();
        $products = Stock::distinct('name')->pluck('name');

        return view('reports.disposed_stocks', [
            'results' => $results,
            'filters' => $filters,
            'products' => $products,
            'totalBuyingPrice' => $results->sum(function ($result) {
                if ($result->stock === null) {
                    return 0;
                }

                return $result->stock->price * $result->quantity_disposed;
            }),
            //    return $result->stock->price * $result->quantity_disposed;
            //}),
        ]);
    }

    public function transferredStocks(Request $request)
    {
        $query = StockMovement::query();

        // Filter by warehouse
        if ($request->warehouse_id) {
            $query->where('to_warehouse_id', $request->warehouse_id);
        }

        // Filter by product
        if ($request->product_name) {
            $query->whereHas('stock', function ($q) use ($request) {
                $q->where('name', $request->product_name);
            });
        }

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        // Fetch the filtered stock movements
        $stockMovements = $query->with(['stock','toWarehouse'])->where('movement_type','transfer')->get();

        //$totalSales = sum($stockMovement->quantity * $stockMovement->stock->selling_price);

        return view('reports.transferred_stocks', [
            'warehouses' => Warehouse::all()->sortBy('name'),
            'products' => Stock::distinct('name')->pluck('name'),
            'stockMovements' => $stockMovements->sortByDesc('created_at'),
            'totalBuyingPrice' => $stockMovements->sum(function ($stockMovement) {
                if ($stockMovement->stock === null) {
                    return 0;
                }
                return $stockMovement->quantity * $stockMovement->stock->price;
            }),
            'totalSales' => $stockMovements->sum(function ($stockMovement) {
                if ($stockMovement->stock === null) {
                    return 0;
                }
                return $stockMovement->quantity * $stockMovement->stock->selling_price;
            }),
            'filters' => $request->all(),
        ]);
    }

    public function dispensedStocks(Request $request)
    {
        $query = StockMovement::query();

        // Filter by branch
        if ($request->branch_id) {
            $query->where('from_branch_id', $request->branch_id);
        }

        // Filter by product
        if ($request->product_name) {
            $query->whereHas('stock', function ($q) use ($request) {
                $q->where('name', $request->product_name);
            });
        }

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        $stockMovements = $query->with(['stock','fromBranch'])->where('movement_type','dispense')->get();

        return view('reports.dispensed_stocks', [
            'branches' => Branch::all()->sortBy('name'),
            'products' => Stock::distinct('name')->pluck('name'),
            'stockMovements' => $stockMovements->sortByDesc('created_at'),
            'totalBuyingPrice' => $stockMovements->sum(function ($stockMovement) {
                if ($stockMovement->stock === null) {
                    return 0;
                }
                return $stockMovement->quantity * $stockMovement->stock->price;
            }),
            'totalSales' => $stockMovements->sum(function ($stockMovement) {
                if ($stockMovement->stock === null) {
                    return 0;
                }
                return $stockMovement->quantity * $stockMovement->stock->selling_price;
            }),
            'filters' => $request->all(),
        ]);
    }
}