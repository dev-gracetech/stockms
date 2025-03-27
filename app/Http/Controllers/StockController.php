<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\StocksImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Stock;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\Replenishment;
use App\Models\Disposal;
use App\Models\SystemSetting as StockSetting;
use App\Models\Category;

class StockController extends Controller
{
    // List all stocks
    public function index()
    {
        $stocks = Stock::with('warehouse')->where('status','active')->get();
        $warehouses = Warehouse::all();
        $categories = Category::all();
        $notifications = auth()->user()->notifications;
        return view('stocks.index', compact('stocks', 'warehouses', 'categories', 'notifications'));
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
            //'quantity' => 'required|integer|min:0',
            'minimum_threshold' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
            'location' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $data = $request->all();

        $stock = Stock::create($data);
        /* $settings = StockSetting::first();

        $warehouse = Branch::where('name', $settings->default_stock_location)->first();

        if ($warehouse) {
            // Attach the stock to the warehouse branch with a default quantity of 100
            $stock->branches()->attach($warehouse->id, ['quantity' => $request->quantity]);
        } */

        //return redirect()->route('stocks.index')->with('success', 'Stock created successfully.');
        return response()->json(['success' => true, 'message' => 'Stock created successfully.']);
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
            'expiry_date' => 'required|date',
            'minimum_threshold' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->all();

        $stock = Stock::findOrFail($id);
        $stock->update($data);

        //return redirect()->route('stocks.index')->with('success', 'Stock updated successfully.');
        return response()->json(['success' => true, 'message' => 'Stock updated successfully.']);
    }

    // Delete a stock
    public function destroy(Stock $stock)
    {
        $stock->delete();
        //return redirect()->route('stocks.index')->with('success', 'Stock deleted successfully.');
        return response()->json(['success' => true, 'message' => 'Stock deleted successfully.']);
    }

    // Replenish stock
    public function replenish(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'source' => 'nullable|string|max:255',
        ]);

        // Add stock to the warehouse
        $quantity_after;
        $stock = Stock::findOrFail($request->stock_id);
        $quantity_before = $stock->quantity;
        $quantity_after = $quantity_before + $request->quantity;
        $stock->quantity += $request->quantity;
        $stock->save();

        // Record the replenishment
        Replenishment::create([
            'warehouse_id' => $stock->warehouse_id,
            'stock_id' => $request->stock_id,
            'quantity_added' => $request->quantity,
            'quantity_before' => $quantity_before,
            'quantity_after' => $quantity_after,
            'source' => $request->source,
        ]);

        //return redirect()->back()->with('success', 'Stock replenished successfully.');
        return response()->json(['success' => true, 'message' => 'Stock replenished successfully.']);
    }

    // Dispose stock
    public function dispose(Request $request, Stock $stock)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Remove stock from the warehouse
        $quantity_before = $stock->quantity;
        $stock->quantity -= $request->quantity;
        //$stock->status = 'disposed';
        $stock->save();

        // Record the disposal
        Disposal::create([
            'warehouse_id' => $stock->warehouse_id,
            //'branch_id' => $stock->branch_id,
            'stock_id' => $stock->id,
            'quantity_before' => $quantity_before,
            'quantity_disposed' => $request->quantity,
            'notes' => $request->notes,
        ]);

        //return redirect()->back()->with('success', 'Stock disposed successfully.');
        return response()->json(['success' => true, 'message' => 'Stock disposed successfully.']);
    }

    public function uploadImage(Request $request, Stock $stock)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/stocks'), $imageName);

            $stock->update(['image' => $imageName]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new StocksImport, $request->file('file'));

        return redirect()->route('stocks.index')->with('success', 'Stocks imported successfully.');
    }
}