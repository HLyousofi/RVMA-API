<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;


class VehicleFilter extends ApiFilter {
    
    protected $safeParms = [
        'brand' => ['eq'],
        'model' => ['eq'],
        'plateNumber' => ['eq'],
        'fuelType' => ['eq', 'ne'],
    ];

    protected $colomnMap = [
        'plateNumber' => 'plate_number',
        'fuelType' => 'fuel_type'
    ];

    protected $operatorMap = [
        'eq' => '=',
        'ne' => '!=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<='
    ];
}