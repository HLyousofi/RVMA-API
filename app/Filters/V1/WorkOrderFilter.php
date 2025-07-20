<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;


class WorkOrderFilter extends ApiFilter {
    
    protected $safeParms = [
        'amount' => ['gt', 'gte', 'eq', 'lt', 'lte'],
        'status' => ['eq','ne'],
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