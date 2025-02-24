<?php

namespace App\Http\Controllers;

use App\Models\StockRequest;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\Branch;
use App\Models\User;
use App\Models\StockMovement;
use App\Notifications\StockRequestNotification;
use Illuminate\Http\Request;

class StockRequestController extends Controller
{
    // Display all stock requests
    public function index()
    {
        $user = auth()->user();
        if ($user->hasRole('admin') or $user->hasRole('warehouse_manager')) {
            $stockRequests = StockRequest::with('branch', 'stock')
                                        ->orderBy('created_at', 'desc')
                                        ->get();
        } else {
            $branch = $user->branches->pluck('id');
            $stockRequests = StockRequest::with('branch', 'stock')
                                        ->where('branch_id', $branch)
                                        ->orderBy('created_at', 'desc')
                                        ->get();
        }
        
        $branches = Branch::all();
        $stocks = Stock::all();
        $notifications = auth()->user()->notifications;
        return view('stock_requests.index', compact('stockRequests', 'branches', 'stocks','notifications'));
    }

    // Create a new stock request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required',
            'stock_id' => 'required',
            'quantity_requested' => 'required|integer|min:1',
        ]);

        $stockRequest = StockRequest::create($validated);

        // Notify admins or warehouse managers
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new StockRequestNotification($stockRequest, 'created'));
        }

        return back()->with('success', 'Stock request created!');
    }

    // Approve a stock request
    public function approve($id)
    {
        $request = StockRequest::findOrFail($id);
        $stock = Stock::findOrFail($request->stock_id);

        if ($stock->quantity >= $request->quantity_requested) {
            // Deduct stock from warehouse
            $stock->quantity -= $request->quantity_requested;
            $stock->save();

            // Create stock movement
            StockMovement::create([
                'stock_id' => $request->stock_id,
                'from_warehouse_id' => $stock->warehouse_id,
                'to_branch_id' => $request->branch_id,
                'quantity' => $request->quantity_requested,
                'movement_type' => 'issue',
            ]);

            // Update request status
            $request->status = 'approved';
            $request->save();

            return redirect()->back()->with('success', 'Stock request approved.');
        }

        return redirect()->back()->with('error', 'Insufficient stock.');
    }

    // Reject a stock request
    public function reject($id)
    {
        $stockRequest = StockRequest::find($id);
        $stockRequest->status = 'rejected';
        $stockRequest->save();

        return back()->with('success', 'Stock request rejected.');
    }
}
