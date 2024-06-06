<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchData extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no', 'amount', 'quantity', 'item', 'client_name', 'client_city', 'date', 'phone', 'status', 'code',
    ];
}
