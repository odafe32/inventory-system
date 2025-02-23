<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'tag_number',
        'name',
        'category_id', // Add this
        'brand',
        'weight',
        'gender',
        'description',
        'stock',
        'size',
        'price',
        'discount',
        'tax',
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'weight' => 'decimal:2'
    ];

    public static function generateTagNumber()
    {
        $prefix = 'PRD';
        $year = date('Y');
        $month = date('m');
        
        // Get the last product created this month
        $lastProduct = self::where('tag_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('tag_number', 'desc')
            ->first();

        if ($lastProduct) {
            // Extract the numeric part and increment
            $lastNumber = (int) substr($lastProduct->tag_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $year . $month . $newNumber;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}