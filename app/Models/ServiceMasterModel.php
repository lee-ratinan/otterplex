<?php

namespace App\Models;

class ServiceMasterModel extends AppBaseModel
{
    protected $table = 'service_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'service_slug',
        'service_name',
        'service_local_name',
        'is_active',
        'price_active_lowest',
        'price_compare_lowest',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}