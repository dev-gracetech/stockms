<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;

class StockMovementController extends Controller
{
    // Display all stock movements
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications;
        if ($user->hasRole('admin') or $user->hasRole('warehouse_manager'))
        {
            $movements = StockMovement::with(['stock', 'fromWarehouse', 'toBranch'])->get();
            return view('stock-movements.index', compact('movements', 'notifications'));
        } 
        else 
        {
            $branch = $user->branches->pluck('id');
            //$movements = StockMovement::with(['stock', 'fromWarehouse', 'toBranch'])->get();
            $stockMovements = StockMovement::whereIn('to_branch_id', $branch)
            ->with(['stock', 'fromWarehouse', 'toBranch'])
            ->get()
            ->groupBy('stock_id')
            ->map(function ($movements) {
                return [
                    'stock' => $movements->first()->stock,
                    'total_quantity' => $movements->sum('quantity'),
                    'movements' => $movements,
                ];
            });
            return view('branches.stock-list', compact('stockMovements', 'notifications'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
