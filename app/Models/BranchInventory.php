<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id', 'stock_id', 'quantity',
    ];

    // Relationship with Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Relationship with Stock
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}