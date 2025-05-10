<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'

    ];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public static function boot()
    {
        parent::boot();

        // Création d'une facture
        static::created(function () {
            Cache::tags(['categories'])->flush();
        });

        // Mise à jour d'une facture
        static::updated(function () {
            Cache::tags(['categories'])->flush();
        });

        // Suppression d'une facture
        static::deleted(function () {
            Cache::tags(['categories'])->flush();
        });
    }
}
