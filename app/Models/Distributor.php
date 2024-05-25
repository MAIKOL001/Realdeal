<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'region',
        'type', // 'rider' or 'agent'
        'address',
        'photo', // path to profile image
    ];

   
}
