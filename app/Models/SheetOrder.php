<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SheetOrder extends Model
{
    use HasFactory;
 

    protected $fillable = [
        'order_no',
        'amount',
        'quantity',
        'item',
        'client_name',
        'client_city',
        'date',
        'phone',
        'agent',
        'status',
        'code',
        'email',
        'invoice_code',
        'sheet_id',
        'sheet_name'
        
        

        // Add more fields as needed
    ];


    protected $casts = [
        'date' => 'date', // Casting 'date' field to date type
    ];

}
