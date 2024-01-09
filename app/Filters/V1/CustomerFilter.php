<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;


class CustomerFilter extends ApiFilter {
    
    protected $safeParms = [
        'name' => ['eq'],
        'type' => ['eq'],
        'adress' => ['eq'],
        'type' => ['eq'],
        'email' => ['eq'],
        'phoneNumber' => ['eq'],
    ];

    protected $colomnMap = [
        'phoneNumber' => 'phone_number'
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