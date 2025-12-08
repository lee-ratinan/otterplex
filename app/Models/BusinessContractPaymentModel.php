<?php

namespace App\Models;

class BusinessContractPaymentModel extends AppBaseModel
{
    protected $table = 'business_contract_payment';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'contract_id',
        'amount_paid',
        'payment_method',
        'payment_notes',
        'staff_comment',
        'payment_status',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}