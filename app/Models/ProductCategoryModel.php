<?php

namespace App\Models;

use App\Models\AppBaseModel;

class ProductCategoryModel extends AppBaseModel
{
    protected $table = 'product_category';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'category_name',
        'category_local_names',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}