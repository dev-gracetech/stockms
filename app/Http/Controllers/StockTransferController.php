<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockTransfer;
use App\Models\Stock;
use App\Models\Branch;

class StockTransferController extends Controller
{
    // List all stock transfers
    public function index()
    {
        $stockTransfers = StockTransfer::with(['stock', 'fromBranch', 'toBranch'])->get();
        return view('stock_transfers.index', compact('stockTransfers'));
    }

    // Show the create stock transfer form
    public function create()
    {
        $stocks = Stock::all();
        $branches = Branch::all();
        return view('stock_transfers.create', compact('stocks', 'branches'));
    }

    // Store a new stock transfer
    public function store(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer|min:1',
        ]);

        StockTransfer::create([
            'stock_id' => $request->stock_id,
            'from_branch_id' => $request->from_branch_id,
            'to_branch_id' => $request->to_branch_id,
            'quantity' => $request->quantity,
            'status' => 'pending', // Default status
        ]);

        return redirect()->route('stock-transfers.index')->with('success', 'Stock transfer created successfully.');
    }

    // Show the edit stock transfer form
    public function edit(StockTransfer $stockTransfer)
    {
        $stocks = Stock::all();
        $branches = Branch::all();
        return view('stock_transfers.edit', compact('stockTransfer', 'stocks', 'branches'));
    }

    // Update a stock transfer
    public function update(Request $request, StockTransfer $stockTransfer)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $stockTransfer->update($request->all());

        return redirect()->route('stock-transfers.index')->with('success', 'Stock transfer updated successfully.');
    }

    // Delete a stock transfer
    public function destroy(StockTransfer $stockTransfer)
    {
        $stockTransfer->delete();
        return redirect()->route('stock-transfers.index')->with('success', 'Stock transfer deleted successfully.');
    }

    // Approve a stock transfer
    public function approve(StockTransfer $stockTransfer)
    {
        // Check if the transfer is already approved or rejected
        if ($stockTransfer->status !== 'pending') {
            return redirect()->route('stock-transfers.index')->with('error', 'This transfer request has already been processed.');
        }

        $stock = Stock::find($stockTransfer->stock_id);
        $fromBranch = Branch::find($stockTransfer->from_branch_id);
        $toBranch = Branch::find($stockTransfer->to_branch_id);
        $quantity = $stockTransfer->quantity;

        // Check if the source branch has enough stock
        if ($fromBranch->stocks()->where('stock_id', $stock->id)->first()->pivot->quantity < $quantity) {
            return redirect()->route('stock-transfers.index')->with('error', 'Insufficient stock in the source branch.');
        }

        // Deduct from source branch
        $fromBranch->stocks()->updateExistingPivot($stock->id, [
            'quantity' => $fromBranch->stocks()->where('stock_id', $stock->id)->first()->pivot->quantity - $quantity
        ]);

        // Add to destination branch
        if ($toBranch->stocks()->where('stock_id', $stock->id)->exists()) {
            $toBranch->stocks()->updateExistingPivot($stock->id, [
                'quantity' => $toBranch->stocks()->where('stock_id', $stock->id)->first()->pivot->quantity + $quantity
            ]);
        } else {
            $toBranch->stocks()->attach($stock->id, ['quantity' => $quantity]);
        }

        // Update transfer status
        $stockTransfer->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Stock transfer approved successfully.');

        // // Update stock quantities
        // $stock = Stock::find($stockTransfer->stock_id);
        // //$fromBranchStock = $stock->branches()->where('branch_id', $stockTransfer->from_branch_id)->first();
        // //$toBranchStock = $stock->branches()->where('branch_id', $stockTransfer->to_branch_id)->first();

        // if ($fromBranchStock->pivot->quantity < $stockTransfer->quantity) {
        //     return redirect()->route('stock-transfers.index')->with('error', 'Insufficient stock in the source branch.');
        // }

        // // Deduct from source branch
        // $fromBranchStock->pivot->quantity -= $stockTransfer->quantity;
        // $fromBranchStock->pivot->save();

        // // Add to destination branch
        // $toBranchStock->pivot->quantity += $stockTransfer->quantity;
        // $toBranchStock->pivot->save();

        // // Update transfer status
        // $stockTransfer->update(['status' => 'approved']);

        // return redirect()->route('stock-transfers.index')->with('success', 'Stock transfer approved successfully.');
    }

    // Reject a stock transfer
    public function reject(StockTransfer $stockTransfer)
    {
        // Check if the transfer is already approved or rejected
        if ($stockTransfer->status !== 'pending') {
            return redirect()->route('stock-transfers.index')->with('error', 'This transfer request has already been processed.');
        }

        // Update transfer status
        $stockTransfer->update(['status' => 'rejected']);

        return redirect()->route('stock-transfers.index')->with('success', 'Stock transfer rejected successfully.');
    }
}