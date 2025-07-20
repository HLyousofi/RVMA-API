<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class CarBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'label'
    ];

    public function vehicles() {
        return $this->hasMany(Vehicle::class);
    }
    public static function boot()
    {
        parent::boot();

        // Création d'une facture
        static::created(function () {
            Cache::tags(['carBrands'])->flush();
        });

        // Mise à jour d'une facture
        static::updated(function () {
            Cache::tags(['carBrands'])->flush();
        });

        // Suppression d'une facture
        static::deleted(function () {
            Cache::tags(['carBrands'])->flush();
        });
    }
}
