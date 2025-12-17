<?php

namespace App\Models;

use App\Models\AppBaseModel;

class ProductVariantInventoryModel extends AppBaseModel
{
    protected $table = 'product_variant_inventory';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'variant_id',
        'activity_key',
        'quantity_change',
        'new_inventory',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}