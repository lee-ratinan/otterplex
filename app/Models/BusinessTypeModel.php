<?php

namespace App\Models;

class BusinessTypeModel extends AppBaseModel
{
    protected $table = 'business_type';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'main_type',
        'type_name',
        'type_local_names',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}