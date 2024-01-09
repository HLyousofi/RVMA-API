<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;


class CategoryFilter extends ApiFilter {
    
    protected $safeParms = [
        'name' => ['eq'],
        'descreption' => ['eq'],
        
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