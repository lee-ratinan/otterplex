<?php

namespace App\Models;

use Config\Services;

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

    public function getVariantInformation(int $variantId): array
    {
        $cache    = Services::cache();
        $cacheKey = 'product_variant_info-' . $variantId;
        if ($cache->get($cacheKey)) {
            return $cache->get($cacheKey);
        }
        $variant = $this->select('product_variant.*, product_master.product_name, product_master.product_local_names')
            ->join('product_master', 'product_variant.product_id = product_master.id')
            ->where('product_variant.id', $variantId)->first();
        if (empty($variant)) {
            return [];
        }
        $variant['product_local_names'] = json_decode($variant['product_local_names'], true);
        $variant['variant_local_names'] = json_decode($variant['variant_local_names'], true);
        $cache->save($cacheKey, $variant, self::HOURS_IN_SEC);
        return $variant;
    }
}