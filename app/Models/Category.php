<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'thumbnail',
        'created_by',
        'tag_id',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (!$category->tag_id) {
                $category->tag_id = self::generateTagId();
            }
        });
    }

    public static function generateTagId()
    {
        $prefix = 'CAT';
        $year = date('y');
        $month = date('m');
        
        $latestCategory = self::where('tag_id', 'like', $prefix . $year . $month . '%')
            ->orderBy('tag_id', 'desc')
            ->first();
        
        if ($latestCategory) {
            $sequence = (int)substr($latestCategory->tag_id, -4);
            $sequence++;
        } else {
            $sequence = 1;
        }
        
        return $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}