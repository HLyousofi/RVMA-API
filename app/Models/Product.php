<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        // 'referance',
        'purchase_price',
        'sale_price'
        
        ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    // public function orders() {
    //     return $this->belongsToMany(Order::class, 'order_products');
    // }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function stocks() {
        return $this->hasMany(Stock::class);
    }


}
