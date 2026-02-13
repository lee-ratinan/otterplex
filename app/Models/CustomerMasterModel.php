<?php

namespace App\Models;

class CustomerMasterModel extends AppBaseModel
{
    protected $table         = 'customer_master';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id',
        'email_address',
        'telephone_number',
        'customer_name',
        'is_active',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * @throws \ReflectionException
     */
    public function checkEmailAddress(array $customerMaster): int
    {
        $existing = $this->where('email_address', $customerMaster['email_address'])->first();
        if (empty($existing)) {
            return $this->insert($customerMaster);
        }
        $update = false;
        if ($customerMaster['telephone_number'] != $existing['telephone_number']) {
            $existing['telephone_number'] = $customerMaster['telephone_number'];
            $update = true;
        }
        if ($customerMaster['customer_name'] != $existing['customer_name']) {
            $existing['customer_name'] = $customerMaster['customer_name'];
            $update = true;
        }
        if ('A' != $existing['is_active']) {
            $existing['is_active'] = 'A';
            $update = true;
        }
        if ($update) {
            $this->update($existing['id'], $existing);
        }
        return $existing['id'];
    }
}