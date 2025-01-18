<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [    
        'vehicle_id',   
        'status',
        'creation_date',
        'expiration_date',
        'comment'
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

}
