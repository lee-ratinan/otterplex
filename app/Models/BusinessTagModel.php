<?php

namespace App\Models;

class BusinessTagModel extends AppBaseModel
{
    protected $table            = 'business_tag';
    protected $primaryKey       = 'business_id';
    protected $useAutoIncrement = false;
    protected $allowedFields    = [
        'business_id',
        'tag_id',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * @param int $businessId
     * @return array
     */
    public function getTagsForBusiness(int $businessId): array
    {
        return $this->select('business_tag.*, tag_master.tag_name')
            ->join('tag_master', 'tag_master.id = business_tag.tag_id')
            ->where('business_id', $businessId)->findAll();
    }
}