<?php

namespace App\Models;

class ServiceStaffModel extends AppBaseModel
{
    protected $table = 'service_staff';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'branch_user_id',
        'service_id',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * @param int $serviceId
     * @return array
     */
    public function getStaffByServiceId(int $serviceId): array
    {
        $raw = $this->select('service_staff.*, service_master.service_name, service_master.service_local_names,
                         branch_user.user_role, branch_master.branch_name, branch_master.branch_local_names,
                         user_master.id AS user_master_id,
                         user_master.user_name_first, user_master.user_name_last')
            ->join('service_master', 'service_master.id = service_staff.service_id')
            ->join('branch_user', 'branch_user.id = service_staff.branch_user_id')
            ->join('branch_master', 'branch_master.id = branch_user.branch_id')
            ->join('user_master', 'user_master.id = branch_user.user_id')
            ->where('service_staff.service_id', $serviceId)
            ->findAll();
        $final = [];
        foreach ($raw as $row) {
            $row['service_local_names'] = json_decode($row['service_local_names'], true);
            $row['branch_local_names']  = json_decode($row['branch_local_names'], true);
            $final[] = $row;
        }
        return $final;
    }

    public function getStaffByServiceAndBranch(int $serviceId, int $branchId): array
    {
        if (0 < $branchId) {
            $this->where('branch_user.branch_id', $branchId);
        }
        $raw = $this->select('service_staff.*, branch_user.branch_id, user_master.id AS user_id, user_master.user_name_first, user_master.user_name_last, user_master.user_public_name')
            ->join('branch_user', 'branch_user.id = service_staff.branch_user_id')
            ->join('user_master', 'user_master.id = branch_user.user_id')
            ->where('service_staff.service_id', $serviceId)
            ->findAll();
        $final = [];
        foreach ($raw as $row) {
            $final[$row['user_id']] = [
                'user_id'          => $row['user_id'],
                'user_full_name'   => $row['user_name_first'] . ' ' . $row['user_name_last'],
                'user_public_name' => $row['user_public_name']
            ];
        }
        return $final;
    }
}