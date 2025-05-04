<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
            'customer_id',
            'brand_id',
            'model',
            'plate_number',
            'fueltype_id'        
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

    public function fuelType() {
        return $this->belongsTo(FuelType::class, 'fueltype_id');
    }

    public function brand() {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }

    public static function boot()
    {
        parent::boot();

        // Création d'une facture
        static::created(function () {
            Cache::tags(['vehicles'])->flush();
        });

        // Mise à jour d'une facture
        static::updated(function () {
            Cache::tags(['vehicles'])->flush();
        });

        // Suppression d'une facture
        static::deleted(function () {
            Cache::tags(['vehicles'])->flush();
        });
    }


}
