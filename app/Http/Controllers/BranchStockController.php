<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Branch;
use App\Models\BranchInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchStockController extends Controller
{
    // Show the stock dispense form
    public function dispenseForm()
    {
        $branches = Branch::all();
        $stocks = BranchInventory::all();
        return view('branches.dispense', compact('branches', 'stocks'));
    }

    // Dispense stock
    public function dispense(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantity' => 'required|integer|min:1',
            'dispensed_to' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $stock = BranchInventory::findOrFail($request->stock_id);

        // Check if the branch has enough stock
        if ($stock->quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Insufficient stock.');
        }

        // Deduct the dispensed quantity from the branch's stock
        $stock->quantity -= $request->quantity;
        $stock->save();

        $user = auth()->user();
        // Record the stock movement
        StockMovement::create([
            'stock_id' => $stock->id,
            'from_branch_id' => $user->branches->pluck('id')->first(),
            'quantity' => $request->quantity,
            'dispensed_to' => $request->dispensed_to,
            'movement_type' => 'dispense',
            'notes' => $request->notes,
        ]);

        return redirect()->route('branch-stock.track')->with('success', 'Stock dispensed successfully.');
    }

    // Track stock movements
    public function track()
    {
        $user = auth()->user();
        if($user->hasrole('admin'))
        {
            $movements = StockMovement::where('movement_type','dispense')
            ->with('stock')
            ->get();
        }
        else
        {
            $branchId = $user->branches->pluck('id')->first();
            $movements = StockMovement::where('from_branch_id', $branchId)
            ->where('movement_type','dispense')
            ->with('stock')
            ->get();
        }
        

        return view('branches.branch-stock-track', compact('movements'));
    }
}
