<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function vehicles() {
        return $this->hasMany(Vehicle::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
