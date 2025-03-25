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
        'workorder_number',
        'vehicle_id', 
        'type',
        'total',
        'invoice_id',  
        'order_date',
        'delivery_date',
        'status',
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
    
        static::creating(function ($workOrder) {
            // Déterminer le préfixe en fonction du type
            $prefix = $workOrder->type === 'quote' ? 'DEVIS-' : 'ORDER-';
    
            // Récupérer le dernier numéro de workorder pour ce type
            $lastWorkOrder = WorkOrder::where('type', $workOrder->type)
                                      ->orderBy('id', 'desc') // Plus sûr que latest()
                                      ->first();
    
            $lastNumber = $lastWorkOrder ? (int) substr($lastWorkOrder->workorder_number, -3) : 0;
    
            // Générer le nouveau numéro de workorder (séquentiel)
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    
            // Définir le numéro de workorder
            $workOrder->workorder_number = $prefix . $newNumber;
        
        });

       
    
        static::updating(function ($workOrder) {
            // Vérifier si le type change de 'quote' à 'order'
            if ($workOrder->isDirty('type') && $workOrder->type === 'order') {
                $prefix = 'ORDER-';
    
                // Récupérer le dernier numéro pour les "orders"
                $lastWorkOrder = WorkOrder::where('type', 'order')
                                          ->orderBy('id', 'desc')
                                          ->first();
                $lastNumber = $lastWorkOrder ? (int) substr($lastWorkOrder->workorder_number, -3) : 0;
    
                // Générer un nouveau numéro
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    
                // Assigner le nouveau numéro
                $workOrder->workorder_number = $prefix . $newNumber;

                $workOrder->updateTotalPrice();
            }
        });
    
        
    }
    

    
  

    

  

    

}
