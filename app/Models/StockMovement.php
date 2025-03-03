<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'from_warehouse_id',
        'to_branch_id',
        'quantity',
        'movement_type',
        'from_branch_id',
        'dispensed_to',
        'notes',
    ];

    // Relationship with Stock
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    // Relationship with Warehouse (source)
    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    // Relationship with Branch (destination)
    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }
}
