<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'from_branch_id',
        'to_branch_id',
        'quantity',
        'status',
    ];

    // Relationship to Stock
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    // Relationship to From Branch
    public function fromBranch()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    // Relationship to To Branch
    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }
}
