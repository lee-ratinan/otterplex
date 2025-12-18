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
}