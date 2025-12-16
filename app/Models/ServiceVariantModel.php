<?php

namespace App\Models;

class ServiceVariantModel extends AppBaseModel
{
    protected $table = 'service_variant';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'service_id',
        'variant_slug',
        'variant_name',
        'variant_local_names',
        'is_active',
        'schedule_type',
        'variant_capacity',
        'price_active',
        'price_compare',
        'required_num_staff',
        'required_resource_type',
        'service_duration_minutes',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}