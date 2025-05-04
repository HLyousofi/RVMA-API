<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class Invoice extends Model
{
    use HasFactory;
    

    protected $fillable = [
            'workorder_id',
            'customer_id',
            'vehicle_id',
            'amount' ,
            'status' ,
            'billed_date',
            'paid_date',
            'invoice_number',
            'discount',

    ];

    

   
    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    public function workorder() {
        return $this->belongsTo(workorder::class);
    }


    public function products()
    {
        return $this->belongsToMany(Product::class, 'invoice_product')
                    ->withPivot('quantity', 'unit_price')
                    ->withTimestamps();
    }

    public function updateTotalPrice()
    {
        $total = $this->products()
                    ->selectRaw('SUM(invoice_product.quantity * invoice_product.unit_price) as total')
                    ->value('total') ?? 0;

        if ($this->total !== $total) {
            $this->updateQuietly(['total' => $total]);
        }
    }

    public static function boot()
    {
        parent::boot();

        // Création d'une facture
        static::created(function () {
            Cache::tags(['invoices'])->flush();
        });

        // Mise à jour d'une facture
        static::updated(function () {
            Cache::tags(['invoices'])->flush();
        });

        // Suppression d'une facture
        static::deleted(function () {
            Cache::tags(['invoices'])->flush();
        });
    }
}
