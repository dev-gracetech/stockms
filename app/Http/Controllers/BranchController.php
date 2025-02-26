<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\BranchInventory;

class BranchController extends Controller
{
    // List all branches
    public function index()
    {
        $branches = Branch::all();
        //$branches = Branch::with('stocks')->get();
        $notifications = auth()->user()->notifications;
        return view('branches.index', compact('branches','notifications'));
    }

    // Show the inventory of a branch
    public function inventory()
    {
        $branch = auth()->user()->branches->pluck('id');
        $stocks = BranchInventory::where('branch_id', $branch)->get();
        return view('branches.stock-list', compact('branch','stocks'));
    }

    // Show the create branch form
    public function create()
    {
        return view('branches.create');
    }

    // Store a new branch
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        Branch::create($request->all());

        //return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
        return response()->json(['success' => 'Branch created successfully!']);
    }

    // Show the edit branch form
    public function edit(Branch $branch)
    {
        //return view('branches.edit', compact('branch'));
        return response()->json([
            'name' => $branch->name,
            'location' => $branch->location,
        ]);
    }

    public function editdata(Branch $branch)
    {
        return response()->json(['branch' => $branch]);
    }

    // Update a branch
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $branch->update($request->all());

        //return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
        return response()->json(['success' => 'Branch updated successfully!']);
    }

    // Delete a branch
    public function destroy(Branch $branch)
    {
        $branch->delete();
        //return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
        return response()->json(['success' => 'Branch deleted successfully!']);
    }

    public function show(Branch $branch)
    {
        $stocks = $branch->stocks;
        return view('branches.show', compact('stocks', 'branch'));    
    }
}
