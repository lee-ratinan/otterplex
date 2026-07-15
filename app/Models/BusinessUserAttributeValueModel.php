<?php

namespace App\Models;

class BusinessUserAttributeValueModel extends AppBaseModel
{
    protected $table = 'business_user_attribute_value';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_user_id',
        'business_user_attribute_id',
        'attribute_value',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

}