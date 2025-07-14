<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'adress',
        'email',
        'phone_number',
        'custom_info',
        'ice'

    ];

    public function invoices() {
        return $this->hasMany(Invoice::class);
    }

    public function contacts() {
        return $this->hasMany(Contact::class);
    }

    public function vehicles() {
        return $this->hasMany(Vehicle::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public static function boot()
    {
        parent::boot();

        // Création d'une facture
        static::created(function () {
            Cache::tags(['customers'])->flush();
        });

        // Mise à jour d'une facture
        // static::updated(function () {
        //     Cache::tags(['customers'])->flush();
        // });

        // Suppression d'une facture
        static::deleted(function () {
            Cache::tags(['customers'])->flush();
        });
    }
}
