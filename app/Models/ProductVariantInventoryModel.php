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

    /**
     * @param int $variantId
     * @param int $start
     * @param int $length
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getDataTable(int $variantId, int $start, int $length, string $startDate, string $endDate): array
    {
        $final    = [];
        $total    = $this->where('variant_id', $variantId)->countAllResults();
        $filtered = $this->where('variant_id', $variantId)
            ->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59')->countAllResults();
        $data     = $this->where('variant_id', $variantId)
            ->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->limit($length, $start)->findAll();
        foreach ($data as $row) {
            $final[] = [
                lang('ProductVariantInventory.enum.activity_key.' . $row['activity_key']),
                number_format($row['quantity_change']),
                number_format($row['new_inventory']),
                format_date_time($row['created_at']),
            ];
        }
        return [
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $final
        ];
    }
}