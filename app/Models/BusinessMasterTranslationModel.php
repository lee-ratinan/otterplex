<?php

namespace App\Models;

use ReflectionException;

class BusinessMasterTranslationModel extends AppBaseModel
{
    protected $table            = 'business_master_translation';
    protected $primaryKey       = 'business_id';
    protected $useAutoIncrement = false;
    protected $allowedFields    = [
        'business_id',
        'language_code',
        'business_name',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Update or insert business name in various languages
     * @param int $businessId
     * @param string[] $businessNames
     * @return bool
     */
    public function updateName(int $businessId, array $businessNames): bool
    {
        $rows = [];
        $currentTime = date('Y-m-d H:i:s');
        foreach ($businessNames as $lang => $name) {
            if (empty($name)) continue;
            $rows[] = [
                'business_id'   => $businessId,
                'language_code' => $lang,
                'business_name' => $name,
                'updated_at'    => $currentTime,
            ];
        }
        if (empty($rows)) {
            return false;
        }
        return $this->db->table($this->table)->upsertBatch($rows);
    }

    /**
     * Get the business name in a specific language
     * @param int $businessId
     * @param string $languageCode
     * @param string $fallbackLanguageCode
     * @return string
     */
    public function getBusinessName(int $businessId, string $languageCode, string $fallbackLanguageCode = 'en'): string
    {
        $languageCode = strtolower($languageCode);
        $row = $this->where('business_id', $businessId)->where('language_code', $languageCode)->first();
        if (empty($row)) {
            $fallbackLanguageCode = strtolower($fallbackLanguageCode);
            $row = $this->where('business_id', $businessId)->where('language_code', $fallbackLanguageCode)->first();
            return $row['business_name'] ?? '';
        }
        return $row['business_name'];
    }
}