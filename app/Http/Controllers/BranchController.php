<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    // List all branches
    public function index()
    {
        //$branches = Branch::all();
        $branches = Branch::with('stocks')->get();
        return view('branches.index', compact('branches'));
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

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
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

    // Update a branch
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    // Delete a branch
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }

    public function show(Branch $branch)
    {
        $stocks = $branch->stocks;
        return view('branches.show', compact('stocks', 'branch'));    
    }
}
