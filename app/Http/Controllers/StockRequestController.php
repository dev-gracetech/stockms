<?php

namespace App\Http\Controllers;

use App\Models\StockRequest;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\Branch;
use App\Models\User;
use App\Models\StockMovement;
use App\Models\BranchInventory;
use App\Notifications\StockRequestNotification;
use App\Models\SystemSetting;
use App\Jobs\SendStockRequestApprovalEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockRequestController extends Controller
{
    // Display all stock requests
    public function index()
    {
        $user = auth()->user();
        $user_branch_id=1;
        if ($user->hasRole('admin') or $user->hasRole('warehouse manager')) {
            $stockRequests = StockRequest::with('branch', 'stock')
                                        ->orderBy('created_at', 'desc')
                                        ->get();
        } else {
            $branch = $user->branches->pluck('id');
            $stockRequests = StockRequest::with('branch', 'stock')
                                        ->where('branch_id', $branch)
                                        ->orderBy('created_at', 'desc')
                                        ->get();
            $user_branch_id = $branch->first();
        }
        
        $branches = Branch::all();
        $stocks = Stock::where('status', 'active')->get();
        $notifications = auth()->user()->notifications;
        return view('stock_requests.index', compact('stockRequests', 'branches', 'user_branch_id', 'stocks','notifications'));
    }

    public function getStockRequests(Request $request)
    {
        $user = auth()->user();
        $user_branch_id=1;
        if ($user->hasRole('admin') or $user->hasRole('warehouse manager')) {
            $query = StockRequest::with('stock', 'branch')
            ->whereHas('stock', function($query) {
                $query->where('deleted_at', NULL);})  
            ->orderBy('created_at', 'desc')
            ->get();
        } else {
         $branch = $user->branches->pluck('id');
         $query = StockRequest::with('stock', 'branch')
            ->where('branch_id', $branch)
            ->whereHas('stock', function($query) {
                $query->where('deleted_at', NULL);})  
            ->orderBy('created_at', 'desc')
            ->get();
        }
            //->select('stock_requests.*');

        return DataTables::of($query)
            ->addColumn('checkbox', function($request) {
                return $request->status === 'pending' 
                    ? '<input type="checkbox" class="request-checkbox" value="'.$request->id.'">' 
                    : '';
            })
            ->addColumn('status', function($request) {
                return view('stock_requests.request-status', compact('request'))->render();
            })
            ->addColumn('actions', function($request) {
                return view('stock_requests.request-actions', compact('request'))->render();
            })
            ->rawColumns(['checkbox', 'status', 'actions'])
            ->make(true);
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

        $stockRequest->reference_id = $stockRequest->getReferenceNumber();
        $stockRequest->save();

        // Notify admins or warehouse managers
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new StockRequestNotification($stockRequest, 'created'));
        }

        // Send email notification to the approver
        // Dispatch the job to send the email
        SendStockRequestApprovalEmail::dispatch($stockRequest);

        //return back()->with('success', 'Stock request created!');
        return response()->json(['success' => true, 'message' => 'Stock request created!']);
    }

    protected function sendApprovalEmail($stockRequest)
    {   
        \App\Jobs\SendStockRequestApprovalEmail::dispatch($stockRequest);
    }
    
    // Approve a stock request
    public function approve($id)
    {
        $request = StockRequest::findOrFail($id);
        $stock = Stock::findOrFail($request->stock_id);


        // Check if the warehouse has enough stock
        if ($stock->quantity < $request->quantity_requested) {
            return redirect()->back()->with('error', 'Insufficient stock in the warehouse.');
        }

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

            // Add stock to the branch inventory
            $branchInventory = BranchInventory::firstOrCreate([
                'branch_id' => $request->branch_id,
                'stock_id' => $request->stock_id,
            ], ['quantity' => 0]);

            $branchInventory->quantity += $request->quantity_requested;
            $branchInventory->save();

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

    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'exists:stock_requests,id',
        ]);
        
        foreach ($validated['request_ids'] as $index => $requestId) {
            $stockrequest = StockRequest::findOrFail($requestId);
            $stock = Stock::findOrFail($stockrequest->stock_id);


            // Check if the warehouse has enough stock
            if ($stock->quantity < $stockrequest->quantity_requested) {
                continue;
                //return redirect()->back()->with('error', 'Insufficient stock in the warehouse.');
            }

            if ($stock->quantity >= $stockrequest->quantity_requested) {
                // Deduct stock from warehouse
                $stock->quantity -= $stockrequest->quantity_requested;
                $stock->save();

                // Create stock movement
                StockMovement::create([
                    'stock_id' => $stockrequest->stock_id,
                    'from_warehouse_id' => $stock->warehouse_id,
                    'to_branch_id' => $stockrequest->branch_id,
                    'quantity' => $stockrequest->quantity_requested,
                    'movement_type' => 'issue',
                ]);

                // Add stock to the branch inventory
                $branchInventory = BranchInventory::firstOrCreate([
                    'branch_id' => $stockrequest->branch_id,
                    'stock_id' => $stockrequest->stock_id,
                ], ['quantity' => 0]);

                $branchInventory->quantity += $stockrequest->quantity_requested;
                $branchInventory->save();

                // Update request status
                $stockrequest->status = 'approved';
                $stockrequest->save();
            }
        }
        return redirect()->back()->with('success', 'Stock requests approved.');
    }

    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'exists:stock_requests,id',
        ]);
        
        foreach ($validated['request_ids'] as $index => $requestId) {
            $stockrequest = StockRequest::findOrFail($requestId);
            $stockrequest->status = 'rejected';
            $stockrequest->save();
        }
        return redirect()->back()->with('success', 'Stock requests rejected.');
    }
}
