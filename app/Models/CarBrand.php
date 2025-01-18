<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'label'
    ];

    public function vehicles() {
        return $this->hasMany(Vehicle::class);
    }
}
