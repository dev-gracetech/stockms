<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'quantity', 'batch', 'expiry_date', 'price', 'location', 'warehouse_id'];
    
    public function stockTransfers()
    {
        return $this->hasMany(StockTransfer::class);
    }

    // Define the many-to-many relationship with Branch
    public function branches()
    {
        return $this->belongsToMany(Branch::class)
            ->withPivot('quantity') // Include the quantity field from the pivot table
            ->withTimestamps();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Relationship with Replenishments
    public function replenishments()
    {
        return $this->hasMany(Replenishment::class);
    }
}
