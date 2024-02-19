<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;


class OrderFilter extends ApiFilter {
    
    protected $safeParms = [
        'vehicleId' => ['eq'],
        'name' => ['eq'],
        'description' => ['eq'],
        'price' => ['gt', 'gte', 'eq', 'lt', 'lte'],
        'status' => ['eq', 'ne']
    ];

    protected $colomnMap = [
        'vehicleId' => 'vehicle_id'
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