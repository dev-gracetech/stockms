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
        return $this->hasMany(Stock::class);
    }

    public function stockRequests()
    {
        return $this->hasMany(StockRequest::class);
    }
}
