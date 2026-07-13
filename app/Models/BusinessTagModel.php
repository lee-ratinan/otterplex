<?php

namespace App\Models;

use CodeIgniter\Config\Services;
use CodeIgniter\Database\BaseResult;
use CodeIgniter\Model;

class BusinessTagModel extends Model
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
            ->where('business_id', $businessId)
            ->orderBy('tag_master.tag_name', 'asc')
            ->findAll();
    }

    /**
     * @param int $businessId
     * @param int $tagId
     * @return BaseResult|bool
     */
    public function deleteTag(int $businessId, int $tagId): BaseResult|bool
    {
        try {
            $row = $this
                ->where('business_id', $businessId)
                ->where('tag_id', $tagId)
                ->first();
            if ($row) {
                $result = $this
                    ->where('business_id', $businessId)
                    ->where('tag_id', $tagId)
                    ->delete();
                if ($result) {
                    $logModel = new LogActivityModel();
                    $logModel->insertLog($this->table, $row['business_id'], $row, LogActivityModel::ACTIVITY_KEY_DELETE);
                }
                return $result;
            }
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return false;
        }
        return false;
    }
}