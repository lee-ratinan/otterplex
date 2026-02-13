<?php

namespace App\Models;

use App\Models\AppBaseModel;

class OrderMasterModel extends AppBaseModel
{
    protected $table         = 'order_master';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'customer_id',
        'customer_address_id',
        'order_number',
        'order_subtotal',
        'order_adjustment',
        'order_total',
        'shipping_option',
        'payment_method',
        'collection_branch_id',
        'order_status',
        'financial_status',
        'shipping_status',
        'staff_comment',
        'customer_comment',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}