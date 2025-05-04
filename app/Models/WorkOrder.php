<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;



class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [    
        'customer_id',
        'quote_number',
        'workorder_number',
        'vehicle_id', 
        'type',
        'total',
        'invoice_id',  
        'order_date',
        'delivery_date',
        'status',
        'current_mileage',
        'expiration_date',
        'comment'
    ];
    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'workorder_product')
                    ->withPivot('quantity', 'unit_price')
                    ->withTimestamps();
    }

    public function updateTotalPrice()
    {
        $total = $this->products()
                    ->selectRaw('SUM(workorder_product.quantity * workorder_product.unit_price) as total')
                    ->value('total') ?? 0;

        if ($this->total !== $total) {
            $this->updateQuietly(['total' => $total]);
        }
    }

    
    

    protected static function boot()
    {
        parent::boot();

        static::created(function ($workOrder) {
            $prefix = $workOrder->type === 'quote' ? 'DEVIS-' : 'ORDER-';
            $cacheTag = $workOrder->type === 'quote' ? 'quotes' : 'orders';
            Cache::tags([$cacheTag])->flush();
            $workOrder->updateQuietly([
                'workorder_number' => $prefix . str_pad($workOrder->id, 3, '0', STR_PAD_LEFT)
            ]);
        });
    
        static::updating(function ($workOrder) {
            if ($workOrder->isDirty('type') && $workOrder->type === 'order' && $workOrder->status == 'pending' ) {
                Cache::tags(['quotes'])->flush();
                Cache::tags(['orders'])->flush();
                $prefix = 'ORDER-';
                $workOrder->workorder_number = $prefix . str_pad($workOrder->id, 3, '0', STR_PAD_LEFT);
                $workOrder->updateTotalPrice();
            }else {
                $cacheTag = $workOrder->type === 'quote' ? 'quotes' : 'orders';
                Cache::tags([$cacheTag])->flush();
            }
        });

        static::deleted(function ($workOrder) {
            $cacheTag = $workOrder->type === 'quote' ? 'quotes' : 'orders';
            Cache::tags([ $cacheTag])->flush();
        });

    
        
    }

   
    

    
  

    

  

    

}
