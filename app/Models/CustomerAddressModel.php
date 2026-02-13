<?php

namespace App\Models;

class CustomerAddressModel extends AppBaseModel
{
    protected $table         = 'customer_address';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id',
        'customer_id',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'address_city',
        'country_code',
        'postal_code',
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
    public function checkAddress(int $customerMasterId, array $customerAddress): int
    {
        $existing = $this->where('customer_id', $customerMasterId)->first();
        if (empty($existing)) {
            $customerAddress['customer_id'] = $customerMasterId;
            return $this->insert($customerAddress);
        }
        $update = false;
        if ($customerAddress['address_line_1'] !== $existing['address_line_1']) {
            $existing['address_line_1'] = $customerAddress['address_line_1'];
            $update = true;
        }
        if ($customerAddress['address_line_2'] !== $existing['address_line_2']) {
            $existing['address_line_2'] = $customerAddress['address_line_2'];
            $update = true;
        }
        if ($customerAddress['address_line_3'] !== $existing['address_line_3']) {
            $existing['address_line_3'] = $customerAddress['address_line_3'];
            $update = true;
        }
        if ($customerAddress['address_city'] !== $existing['address_city']) {
            $existing['address_city'] = $customerAddress['address_city'];
            $update = true;
        }
        if ($customerAddress['country_code'] !== $existing['country_code']) {
            $existing['country_code'] = $customerAddress['country_code'];
            $update = true;
        }
        if ($customerAddress['postal_code'] !== $existing['postal_code']) {
            $existing['postal_code'] = $customerAddress['postal_code'];
            $update = true;
        }
        if ($update) {
            $this->update($existing['id'], $existing);
        }
        return $existing['id'];
    }
}