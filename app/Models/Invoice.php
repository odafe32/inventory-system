<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'created_by',
        'sender_name',
        'sender_address',
        'sender_phone',
        'issue_from',
        'issue_from_address',
        'issue_from_phone',
        'issue_from_email',
        'issue_for',
        'issue_for_address',
        'issue_for_phone',
        'issue_for_email',
        'issue_date',
        'due_date',
        'amount',
        'subtotal',
        'discount',
        'tax',
        'grand_total',
        'status',
        'logo'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate a unique invoice number
     */
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-';
        $dateCode = date('Ymd');
        
        // Get the last invoice number
        $lastInvoice = self::orderBy('id', 'desc')->first();
        
        if ($lastInvoice) {
            // Extract the numeric part of the last invoice number
            $lastNumber = substr($lastInvoice->invoice_number, strrpos($lastInvoice->invoice_number, '/') + 1);
            $nextNumber = intval($lastNumber) + 1;
        } else {
            $nextNumber = 1;
        }
        
        // Format the number with leading zeros
        $formattedNumber = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        
        return "#" . $prefix . $dateCode . '/' . $formattedNumber;
    }
}