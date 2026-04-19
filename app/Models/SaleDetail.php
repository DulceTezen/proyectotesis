<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'price',
        'quantity'
    ];

    public $timestamps = false;

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
