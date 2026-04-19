<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'bussiness_name',
        'bussiness_document',
        'phone',
        'voucher',
        'direction',
        'city',
        'district',
        'address',
        'reference',
        'number',
        'client_id',
        'delivery_price',
        'total',
        'payment_method_id',
        'delivery_id',
        'date',
        'status',
        'deleted',
    ];

    public $timestamps = false;

    public function scopeActive($query){
        return $query->where('deleted', 0);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function details(){
        return $this->hasMany(SaleDetail::class);
    }

    public function delivery(){
        return $this->belongsTo(Delivery::class);
    }

    public function payment_method(){
        return $this->belongsTo(PaymentMethod::class);
    }
}
