<?php

namespace App\Models;

class BusinessUserAttributeModel extends AppBaseModel
{
    protected $table = 'business_user_attribute';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'attribute_name',
        'attribute_local_name',
        'data_type',
        'data_list',
        'data_unit',
        'in_use',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * @return string[]
     */
    public function getDataTypes(): array
    {
        return ['num', 'text', 'translated_text', 'true-false', 'list'];
    }

    /**
     * @return string[]
     */
    public function getInUseValues(): array
    {
        return ['Y', 'N'];
    }

}