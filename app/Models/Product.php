<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function caterory() {
        return $this->belongsTo(Caterory::class);
    }

    public function orders() {
        return $this->belongsToMany(Order::class, 'order_products');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }


}
