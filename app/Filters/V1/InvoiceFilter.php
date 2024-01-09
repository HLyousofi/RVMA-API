<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;


class InvoiceFilter extends ApiFilter {
    
    protected $safeParms = [
        'amount' => ['gt', 'gte', 'eq', 'lt', 'lte'],
        'status' => ['eq','ne'],
        'billedDate' => ['gt', 'gte', 'eq', 'lt', 'lte'],
        'paidDate' => ['gt', 'gte', 'eq', 'lt', 'lte'],
    ];

    protected $colomnMap = [
        'billedDate' => 'billed_date',
        'paidDate' => 'paid_date',
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