<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;


class VehicleFilter extends ApiFilter {
    
    protected $safeParms = [
        'brand' => ['eq'],
        'model' => ['eq'],
        'plateNumber' => ['eq'],
        'fuelType' => ['eq', 'ne'],
        'customerId' => ['eq']
    ];

    protected $colomnMap = [
        'plateNumber' => 'plate_number',
        'fuelType' => 'fuel_type',
        'customerId' => 'customer_id'
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