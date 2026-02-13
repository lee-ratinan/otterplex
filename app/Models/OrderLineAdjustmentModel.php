<?php

namespace App\Models;

use App\Models\AppBaseModel;

class OrderLineAdjustmentModel extends AppBaseModel
{
    protected $table         = 'order_line_item';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id',
        'order_id',
        'adjustment_type',
        'line_detail',
        'line_amount',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}