<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_code',
        'parent_product_id',
        'quantity',
        'date_generated',
        'product_unit_id',
        'status', // Include the status field in the fillable array
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'parent_product_id');
    }

    // Add any other relationships or methods here
}
