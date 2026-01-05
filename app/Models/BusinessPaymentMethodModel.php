<?php

namespace App\Models;

use CodeIgniter\Config\Services;

class BusinessPaymentMethodModel extends AppBaseModel
{
    protected $table = 'business_payment_method';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'payment_method',
        'payment_instruction',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_methods_for_business(): array
    {
        $session    = session();
        $businessId = $session->business['business_id'];
        $cache      = Services::cache();
        $cacheKey   = 'business_payment_methods-for-' . $businessId;
        if ($cache->get($cacheKey)) {
            return $cache->get($cacheKey);
        }
        $results = $this->where('business_id', $businessId)->findAll();
        $final   = [];
        foreach ($results as $result) {
            $result['payment_instruction']    = json_decode($result['payment_instruction'], true);
            $final[$result['payment_method']] = $result;
        }
        $cache->save($cacheKey, $final, self::HOURS_IN_SEC);
        return $final;
    }
}