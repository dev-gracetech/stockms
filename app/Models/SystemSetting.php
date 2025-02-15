<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'company_logo',
        'high_stock_threshold',
        'low_stock_threshold',
        'default_stock_location',
        'notification_email',
        'currency',
    ];
}
