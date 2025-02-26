<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replenishment extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id', 'stock_id', 'quantity_added', 'quantity_before', 'quantity_after', 'source',
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
}
