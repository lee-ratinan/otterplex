<?php

namespace App\Models;

class BusinessShippingFeeModel extends AppBaseModel
{
    protected $table = 'business_shipping_fee';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'price_range_from',
        'price_range_to',
        'shipping_rate',
        'rate_comment',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}