<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'name',
        'last_name',
        'document',
        'direction',
        'city',
        'address',
        'phone',
        'email',
        'product_type',
        'description',
        'amount',
        'order_number',
        'claim',
        'client_request',
        'date'
    ];

    public $timestamps = false;
}
