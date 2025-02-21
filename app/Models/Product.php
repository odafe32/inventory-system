<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'tag_number',
        'name',
        'category',
        'brand',
        'weight',
        'gender',
        'description',
        'stock',
        'tags',
        'size',
        'price',
        'discount',
        'tax',
        'image'
    ];

    protected $casts = [
        'tags' => 'array'
    ];
}