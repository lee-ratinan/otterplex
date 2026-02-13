<?php

namespace App\Models;

use App\Models\AppBaseModel;

class OrderLineItemModel extends AppBaseModel
{
    protected $table         = 'order_line_item';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id',
        'order_id',
        'product_variant_id',
        'session_id',
        'product_name',
        'product_variant_name',
        'line_quantity',
        'unit_price',
        'line_subtotal',
        'item_need_delivery',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}