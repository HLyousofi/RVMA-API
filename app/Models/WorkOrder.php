<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


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

    public function products()
    {
        return $this->belongsToMany(Product::class, 'workOrder_product')
                    ->withPivot('quantity', 'unit_price')
                    ->withTimestamps();
    }

    public function updateTotalPrice()
    {
        $total = $this->products()
                    ->selectRaw('SUM(workOrder_product.quantity * workOrder_product.unit_price) as total')
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
            $workOrder->updateQuietly([
                'workorder_number' => $prefix . str_pad($workOrder->id, 3, '0', STR_PAD_LEFT)
            ]);
        });
    
        static::updating(function ($workOrder) {
            if ($workOrder->isDirty('type') && $workOrder->type === 'order' && $workOrder->status == 'pending' ) {
                $prefix = 'ORDER-';
                $workOrder->workorder_number = $prefix . str_pad($workOrder->id, 3, '0', STR_PAD_LEFT);
                $workOrder->updateTotalPrice();
            }
        });
    
        
    }
    

    
  

    

  

    

}
