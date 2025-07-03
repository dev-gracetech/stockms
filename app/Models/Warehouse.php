<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location'];

    public function stocks()
    {
        return $this->hasMany(Stock::class)
            ->withPivot('quantity', 'minimum_threshold')
            ->withTimestamps();
    }

    public function stockRequests()
    {
        return $this->hasMany(StockRequest::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'warehouse_user');
    }
}
