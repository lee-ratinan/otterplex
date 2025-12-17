<?php

namespace App\Models;

class ProductMasterModel extends AppBaseModel
{
    protected $table = 'product_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'product_category_id',
        'product_slug',
        'product_name',
        'product_local_names',
        'product_tag',
        'product_type',
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