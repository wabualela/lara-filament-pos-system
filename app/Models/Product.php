<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'price',
        'note',
        'product_categories_id',
        'units_id'
    ];

    function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_categories_id');
    }

    function unit()
    {
        return $this->belongsTo(Unit::class, 'units_id');
    }
}
