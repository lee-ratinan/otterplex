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
        'my_default_business',
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
    const string MY_DEFAULT_BUSINESS_YES = 'Y';
    const string MY_DEFAULT_BUSINESS_NO = 'N';

    /**
     * Get business by user IDs
     * @param int $userId
     * @param bool $onlyActive (optional) Only retrieve active businesses
     * @param string $business_slug (optional) Only try to search for this slug if not empty
     * @return array
     */
    public function getBusinessesByUserId(int $userId, bool $onlyActive = false, string $business_slug = ''): array
    {
        if ($onlyActive) {
            $todayDate = date(DATE_FORMAT_DB);
            $this->where('contract_expiry >=', $todayDate)
                ->where('role_status', self::ROLE_STATUS_ACTIVE);
        }
        if (!empty($business_slug)) {
            $this->where('business_master.business_slug', $business_slug);
        }
        $businesses = $this->select('business_user.*, business_master.business_type_id, business_master.business_name,
                business_master.business_slug, business_master.business_local_names, business_master.country_code,
                business_master.currency_code, business_master.tax_percentage, business_master.tax_inclusive, business_master.contract_anchor_day, business_master.contract_expiry')
            ->join('business_master', 'business_master.id = business_user.business_id')
            ->where('user_id', $userId)
            ->findAll();
        for ($i = 0; $i < count($businesses); $i++) {
            $businesses[$i]['business_local_names'] = json_decode($businesses[$i]['business_local_names'], true);
        }
        return $businesses;
    }

    /**
     * @param int $businessId
     * @param bool $onlyActive
     * @return array
     */
    public function getUsersByBusinessId(int $businessId, bool $onlyActive = false): array
    {
        if ($onlyActive) {
            $this->where('role_status', self::ROLE_STATUS_ACTIVE);
        }
        return $this->select('business_user.*, user_master.user_name_first, user_master.user_name_last, user_master.email_address, user_master.account_status')
            ->join('user_master', 'user_master.id = business_user.user_id')
            ->where('business_id', $businessId)
            ->findAll();
    }
}