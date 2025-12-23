<?php

namespace App\Models;

use Config\Services;
use ReflectionException;

class ServiceMasterModel extends AppBaseModel
{
    protected $table = 'service_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'service_slug',
        'service_name',
        'service_local_names',
        'is_active',
        'price_active_lowest',
        'price_compare_lowest',
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
    public function getServicesForBusiness(int $businessId): array
    {
        $cache    = Services::cache();
        $cacheKey = 'services_for_business_id-' . $businessId;
        if ($cache->get($cacheKey)) {
            return $cache->get($cacheKey);
        }
        $services = $this->where(['business_id' => $businessId])->findAll();
        $final    = [];
        foreach ($services as $service) {
            $final[]     = [
                'id'                   => $service['id'],
                'service_slug'         => $service['service_slug'],
                'service_name'         => $service['service_name'],
                'service_local_names'  => json_decode($service['service_local_names'], true),
                'is_active'            => $service['is_active'],
            ];
        }
        $cache->save($cacheKey, $final, self::HOURS_IN_SEC);
        return $final;
    }

    /**
     * @param int $serviceId
     * @return bool
     * @throws ReflectionException
     */
    public function updateLowestPrices(int $serviceId): bool
    {
        $variantModel  = new ServiceVariantModel();
        $variants      = $variantModel->where('service_id', $serviceId)->findAll();
        $actualPrices  = [];
        $comparePrices = [];
        foreach ($variants as $variant) {
            $actualPrices[]  = $variant['price_active'];
            $comparePrices[] = $variant['price_compare'];
        }
        log_message('debug', 'Find lowest prices: ' . $serviceId);
        log_message('debug', json_encode($actualPrices));
        log_message('debug', json_encode($comparePrices));
        $lowestActual  = min($actualPrices);
        $lowestCompare = min($comparePrices);
        log_message('debug', 'Lowest actual: ' . $lowestActual);
        log_message('debug', 'Lowest compare: ' . $lowestCompare);
        return $this->update($serviceId, [
            'price_active_lowest'  => $lowestActual,
            'price_compare_lowest' => $lowestCompare
        ]);
    }
}