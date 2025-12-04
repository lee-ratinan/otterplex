<?php

namespace App\Models;

class BusinessUserModel extends AppBaseModel
{
    protected $table = 'business_user';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'user_id',
        'user_role',
        'role_status',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const string USER_ROLE_OWNER = 'OWNER';
    const string USER_ROLE_MANAGER = 'MANAGER';
    const string USER_ROLE_STAFF = 'STAFF';
    const string ROLE_STATUS_REQUESTED = 'REQUESTED';
    const string ROLE_STATUS_ACTIVE = 'ACTIVE';
    const string ROLE_STATUS_REVOKED = 'REVOKED';

    /**
     * Get business by user IDs
     * @param $userId
     * @return array
     */
    public function getBusinessesByUserId($userId): array
    {
        return $this->select('business_user.*, business_master.business_type_id, business_master.business_name,
                business_master.business_slug, business_master.business_local_names, business_master.country_code,
                business_master.currency_code, business_master.tax_percentage, business_master.tax_inclusive, business_master.contract_expiry')
            ->join('business_master', 'business_master.id = business_user.business_id')
            ->where('user_id', $userId)
            ->where('role_status', self::ROLE_STATUS_ACTIVE)
            ->findAll();
    }
}