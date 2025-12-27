<?php

namespace App\Models;

use ReflectionException;

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
        'product_image',
        'product_description',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * @param int $businessId
     * @return array
     */
    public function getDataTable(int $businessId): array
    {
        $session = session();
        $raw     = $this->select('product_master.*, product_category.category_name, product_category.category_local_names')
            ->join('product_category', 'product_category.id = product_master.product_category_id')
            ->where('product_master.business_id', $businessId)->findAll();
        $final   = [];
        foreach ($raw as $row) {
            $category_local = json_decode($row['category_local_names'], true);
            $product_local  = json_decode($row['product_local_names'], true);
            $category_name  = $category_local[$session->lang] ?? $row['category_name'];
            $product_name   = $product_local[$session->lang] ?? $row['product_name'];
            $final[]  = [
                $category_name,
                $product_name,
                lang('ProductMaster.enum.product_type.' . $row['product_type']),
                lang('ProductMaster.enum.is_active.' . $row['is_active']),
                '<a class="btn btn-primary btn-sm float-end" href="' . base_url('admin/product/' . ($row['id'] * ID_MASKED_PRIME)) . '">' . lang('System.buttons.edit') . '</a>'
            ];
        }
        return $final;
    }

    /**
     * @param int $productId
     * @return bool
     * @throws ReflectionException
     */
    public function updateLowestPrices(int $productId): bool
    {
        $variantModel  = new ProductVariantModel();
        $variants      = $variantModel->where('product_id', $productId)->findAll();
        $actualPrices  = [];
        $comparePrices = [];
        foreach ($variants as $variant) {
            $actualPrices[]  = $variant['price_active'];
            $comparePrices[] = $variant['price_compare'];
        }
        log_message('debug', 'Find lowest prices: ' . $productId);
        log_message('debug', json_encode($actualPrices));
        log_message('debug', json_encode($comparePrices));
        $lowestActual  = min($actualPrices);
        $lowestCompare = min($comparePrices);
        log_message('debug', 'Lowest actual: ' . $lowestActual);
        log_message('debug', 'Lowest compare: ' . $lowestCompare);
        return $this->update($productId, [
            'price_active_lowest'  => $lowestActual,
            'price_compare_lowest' => $lowestCompare
        ]);
    }
}