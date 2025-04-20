<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;
    protected $table = 'invoice_product';

    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public static function boot()
    {
        parent::boot();
    
        static::saved(function ($invoiceProduct) {
            $invoiceProduct->invoice->updateTotalPrice();
        });
    
        static::deleted(function ($invoiceProduct) {
            $invoiceProduct->invoice->updateTotalPrice();
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
