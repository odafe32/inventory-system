<?php
// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'customer_name',
        'email',
        'phone',
        'address',
        'priority',
        'payment_status',
        'order_status',
        'subtotal',
        'tax',
        'total'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public static function generateUniqueOrderId()
    {
        do {
            $orderId = '#' . date('ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('order_id', $orderId)->exists());

        return $orderId;
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
        public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
}