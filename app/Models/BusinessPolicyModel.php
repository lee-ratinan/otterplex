<?php

namespace App\Models;

use CodeIgniter\Config\Services;

class BusinessPolicyModel extends AppBaseModel
{
    protected $table = 'business_policy';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'language_code',
        'policy_type',
        'policy_text',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_policy_types(): array
    {
        return ['privacy', 'tnc', 'cancel', 'return', 'refund', 'payment', 'shipping'];
    }
}