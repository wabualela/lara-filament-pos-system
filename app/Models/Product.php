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
        'product_categories_id'
    ];

    function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_categories_id');
    }
}
