<?php

namespace App\Models;

class BranchUserModel extends AppBaseModel
{
    protected $table = 'branch_user';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'branch_id',
        'user_id',
        'user_role',
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
    public function getUsersByBusinessId(int $businessId): array
    {
        return $this->select('branch_user.*, branch_master.branch_name, branch_master.branch_local_names')
            ->join('branch_master', 'branch_master.id = branch_user.branch_id')
            ->where('branch_master.business_id', $businessId)
            ->findAll();
    }

    /**
     * @param int $userId
     * @param int $businessId
     * @return array
     */
    public function getUserByBusinessId(int $userId, int $businessId): array
    {
        return $this->select('branch_user.*, branch_master.branch_name, branch_master.branch_local_names')
            ->join('branch_master', 'branch_master.id = branch_user.branch_id')
            ->where('branch_master.business_id', $businessId)
            ->where('branch_user.user_id', $userId)
            ->findAll();
    }
}