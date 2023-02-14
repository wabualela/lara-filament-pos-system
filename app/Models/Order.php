<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'sub_total',
        'vat',
        'total',
        'quantity',
        'pay',
        'due',
        'paid_by',
        'order_date',
        'order_month',
        'order_year',
    ];

    function client()
    {
        return $this->belongsTo(Client::class);
    }

    function product()
    {
        return $this->belongsTo(Product::class);
    }
}
