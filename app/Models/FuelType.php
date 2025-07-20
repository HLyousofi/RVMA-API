<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class FuelType extends Model
{
    use HasFactory;

    protected $fillable = [
        'fuel_type'
    ];

    public function vehicles() {
        return $this->hasMany(Vehicle::class);
    }

    public static function boot()
    {
        parent::boot();

        // Création d'une facture
        static::created(function () {
            Cache::tags(['fuelType'])->flush();
        });

        // Mise à jour d'une facture
        static::updated(function () {
            Cache::tags(['fuelType'])->flush();
        });

        // Suppression d'une facture
        static::deleted(function () {
            Cache::tags(['fuelType'])->flush();
        });
    }
}
