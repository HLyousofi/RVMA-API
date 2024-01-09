<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
            'customer_id',
            'brand',
            'model',
            'plate_number',
            'fuel_type'        
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function quotes() {
        return $this->hasMany(Quote::class);
    }


}
