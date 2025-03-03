<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'branch_id',
        'stock_id',
        'quantity_before',
        'quantity_disposed',
        'notes',
    ];

    // Relationship with Warehouse
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Relationship with Stock
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    // Relationship with Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
