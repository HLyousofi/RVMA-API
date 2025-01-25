<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteProduct extends Model
{
    use HasFactory;

    protected $fillable = [    
        'quote_id',   
        'product_id',
        'quantity',
        'line_price',
        'unit_price',
        
    ];

    public function quote() {
        return $this->belongsTo(Quote::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

}
