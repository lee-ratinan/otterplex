<?php

namespace App\Models;

use CodeIgniter\Config\Services;

class OtternautPackageModel extends AppBaseModel
{
    protected $table = 'otternaut_package';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'country_code',
        'package_name',
        'package_monthly_price',
        'package_annual_price',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getOtternautPackageForCountry(string $countryCode): array
    {
        $cache       = Services::cache();
        $countryCode = strtoupper($countryCode);
        $cacheKey    = 'otternaut_package-' . $countryCode;
        if ($cache->get($cacheKey)) {
            return $cache->get($cacheKey);
        }
        $packages    = $this->where('country_code', $countryCode)->where('package_monthly_price >', 0)->orderBy('id')->findAll();
        $cache->save($cacheKey, $packages, self::HOURS_IN_SEC);
        return $packages;
    }
}