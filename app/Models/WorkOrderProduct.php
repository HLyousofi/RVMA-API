<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteProduct extends Model
{
    use HasFactory;

    protected $table = 'workOrder_product';

    protected $fillable = [    
        'work_order_id',   
        'product_id',
        'quantity',
        'line_price',
        'unit_price',
        
    ];

    public static function boot()
{
    parent::boot();

    static::saved(function ($workorderProduct) {
        $workorderProduct->workorder->updateTotalPrice();
    });

    static::deleted(function ($workorderProduct) {
        $workorderProduct->workorder->updateTotalPrice();
    });
}




    public function workOrder() {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

}
