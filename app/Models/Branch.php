<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location'];

    // Define the many-to-many relationship with Stock
    public function stocks()
    {
        return $this->belongsToMany(Stock::class)
            ->withPivot('quantity') // Include the quantity field from the pivot table
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'branch_user');
    }

    public function stockRequests()
    {
        return $this->hasMany(StockRequest::class);
    }
}
