<?php

namespace App\Models;

class ProductVariantModel extends AppBaseModel
{
    protected $table = 'product_variant';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'product_id',
        'variant_slug',
        'variant_sku',
        'variant_name',
        'variant_local_names',
        'is_active',
        'inventory_count',
        'price_active',
        'price_compare',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}