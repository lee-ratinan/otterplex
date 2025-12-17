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

    /**
     * @param int $businessId
     * @return array
     */
    public function getDataTable(int $businessId): array
    {
        $session = session();
        $raw     = $this->where('business_id', $businessId)->findAll();
        $final   = [];
        foreach ($raw as $row) {
            $local         = json_decode($row['category_local_names'], true);
            $category_name = $local[$session->lang] ?? $row['category_name'];
            $final[]       = [
                $category_name,
                '<a class="btn btn-primary btn-sm float-end" href="' . base_url('admin/product/category/' . ($row['id'] * ID_MASKED_PRIME)) . '">' . lang('System.buttons.edit') . '</a>'
            ];
        }
        return $final;
    }
}