<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\SystemSetting as StockSetting;

class StockController extends Controller
{
    // List all stocks
    public function index()
    {
        $stocks = Stock::with('warehouse')->get();
        $warehouses = Warehouse::all(); // For creating and updating stock items
        return view('stocks.index', compact('stocks', 'warehouses'));
    }

    // Show the create stock form
    public function create()
    {
        return view('stocks.create');
    }

    // Store a new stock
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'batch' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $stock = Stock::create($request->all());
        /* $settings = StockSetting::first();

        $warehouse = Branch::where('name', $settings->default_stock_location)->first();

        if ($warehouse) {
            // Attach the stock to the warehouse branch with a default quantity of 100
            $stock->branches()->attach($warehouse->id, ['quantity' => $request->quantity]);
        } */

        return redirect()->route('stocks.index')->with('success', 'Stock created successfully.');
    }

    // Show the edit stock form
    public function editdata(Stock $stock)
    {
        //return view('stocks.edit', compact('stock'));
        return response()->json(['stock' => $stock]);
    }

    // Update a stock
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'batch' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
            'price' => 'required|numeric|min:0',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update($request->all());

        return redirect()->route('stocks.index')->with('success', 'Stock updated successfully.');
    }

    // Delete a stock
    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('stocks.index')->with('success', 'Stock deleted successfully.');
    }
}