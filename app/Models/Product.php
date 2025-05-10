<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' ,
        'category_id',
        'brand',
        'model',
        'manufacturer_reference',
        'oem_reference',
        'description' ,
        'purchase_price',
        'sale_price'
        
        ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function stocks() {
        return $this->hasMany(Stock::class);
    }

    public function quotes()
    {
        return $this->belongsToMany(WorkOrder::class, 'workorder_product')
                    ->withPivot('quantity', 'unit_price')
                    ->withTimestamps();
    }

    public static function boot()
    {
        parent::boot();

        // Création d'une facture
        static::created(function () {
            Cache::tags(['products'])->flush();
        });

        // Mise à jour d'une facture
        static::updated(function () {
            Cache::tags(['products'])->flush();
        });

        // Suppression d'une facture
        static::deleted(function () {
            Cache::tags(['products'])->flush();
        });
    }


}
