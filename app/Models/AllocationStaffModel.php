<?php

namespace App\Models;

use App\Models\AppBaseModel;

class AllocationStaffModel extends AppBaseModel
{
    protected $table = 'allocation_staff';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'user_id',
        'session_breakdown_id',
        'allocation_type',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}