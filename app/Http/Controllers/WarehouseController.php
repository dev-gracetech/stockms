<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    // Display all warehouses
    public function index()
    {
        $warehouses = Warehouse::all();
        return view('warehouses.index', compact('warehouses'));
    }

    // Store a new warehouse
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
        ]);

        Warehouse::create($request->all());

        //return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully!');
        return response()->json(['success' => 'Warehouse created successfully!']);
    }

    // Show the form for editing the warehouse
    public function editdata(Warehouse $warehouse)
    {
        //return view('warehouses.edit', compact('warehouse'));
        return response()->json(['warehouse' => $warehouse]);
    }

    // Update the warehouse
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
        ]);

        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update($request->all());

        //return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully!');
        return response()->json(['success' => 'Warehouse updated successfully!']);
    }

    // Delete the warehouse
    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        return response()->json(['success' => 'Warehouse deleted successfully!']);
    }
}