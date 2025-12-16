<?php

namespace App\Models;

class ServiceStaffModel extends AppBaseModel
{
    protected $table = 'service_staff';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'branch_user_id',
        'service_id',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}