<?php

namespace App\Models;

use App\Models\AppBaseModel;

class OrderPaymentModel extends AppBaseModel
{
    protected $table         = 'order_payment';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id',
        'order_id',
        'amount_paid',
        'payment_method',
        'payment_notes',
        'staff_comment',
        'payment_status',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}