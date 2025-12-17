<?php

namespace App\Models;

use CodeIgniter\Config\Services;

class ServiceVariantModel extends AppBaseModel
{
    protected $table = 'service_variant';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'service_id',
        'variant_slug',
        'variant_name',
        'variant_local_names',
        'is_active',
        'schedule_type',
        'variant_capacity',
        'price_active',
        'price_compare',
        'required_num_staff',
        'required_resource_type_id',
        'service_duration_minutes',
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
    public function getVariantsForService(int $serviceId): array
    {
        $cache    = Services::cache();
        $session  = session();
        $cacheKey = 'variants_for_service_id-' . $serviceId;
        if ($cache->get($cacheKey)) {
            return $cache->get($cacheKey);
        }
        $variants = $this->select('service_variant.*, resource_type.resource_type, resource_type.resource_local_names')
            ->join('resource_type', 'service_variant.required_resource_type_id = resource_type.id', 'left')
            ->where(['service_id' => $serviceId])->findAll();
        $final    = [];
        foreach ($variants as $variant) {
            $resource_local_names = json_decode($variant['resource_local_names'], true);
            $resource_type        = $resource_local_names[$session->lang] ?? $variant['resource_type'];
            $final[]              = [
                'id'                        => $variant['id'],
                'variant_slug'              => $variant['variant_slug'],
                'variant_name'              => $variant['variant_name'],
                'variant_local_names'       => json_decode($variant['variant_local_names'], true),
                'is_active'                 => $variant['is_active'],
                'schedule_type'             => $variant['schedule_type'],
                'variant_capacity'          => $variant['variant_capacity'],
                'price_active'              => $variant['price_active'],
                'price_compare'             => $variant['price_compare'],
                'required_num_staff'        => $variant['required_num_staff'],
                'required_resource_type_id' => $variant['required_resource_type_id'],
                'resource_type'             => $resource_type,
                'service_duration_minutes'  => $variant['service_duration_minutes'],
            ];
        }
        $cache->save($cacheKey, $final, self::HOURS_IN_SEC);
        return $final;
    }
}