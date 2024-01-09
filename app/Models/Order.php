<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'invoice_id',
        'customer_id',
        'quote_id',
        'name' ,
        'description',
        'price' ,
        'task',
        'status'
    ];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function quote() {
        return $this->belongsTo(Quote::class);
    }

    public function products() {
        return $this->belongsToMany(Products::class, 'orders_products');
    }

}
