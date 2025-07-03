<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['name', 'quantity', 'minimum_threshold', 'batch', 'expiry_date', 'price','selling_price', 
    'location', 'warehouse_id', 'category_id', 'status', 'image'];
    
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

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class)
            ->withPivot('quantity', 'minimum_threshold')
            ->withTimestamps();
    }

    public function getWarehouseQuantityAttribute()
    {
        return $this->warehouses()->where('warehouse_id', 1)->first()->pivot->quantity ?? 0; // Use optional chaining for null check and default value
    }

    public function warehouse_qty($warehouseId)
    {
        return $this->warehouses()->where('warehouse_id', $warehouseId)->first()->pivot->quantity ?? 0; // Use optional chaining for null check and default value
    }
    // Get total quantity across all warehouses
    public function getTotalQuantityAttribute()
    {
        return $this->warehouses->sum('pivot.quantity');
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

     // Relationship with Disposals
     public function disposals()
     {
         return $this->hasMany(Disposal::class);
     }

     public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('images/stocks/' . $this->image);
        }
        return asset('images/default_stock_image.png'); // Path to the default image
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
