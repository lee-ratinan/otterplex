<?php

namespace App\Models;

class BusinessTypeModel extends AppBaseModel
{
    protected $table = 'business_type';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'main_type',
        'type_name',
        'type_local_names',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Retrieve business type data
     * @return array
     */
    public function retrieveData(): array
    {
        $session = session();
        $locale  = $session->lang;
        $allData = $this->findAll();
        $final   = [];
        foreach ($allData as $data) {
            $main_type          = lang('BusinessType.enum.main_type.' . $data['main_type']);
            $names              = json_decode($data['type_local_names'], true);
            $final[$data['id']] = $main_type . ' - ' . ($names[$locale] ?? $data['type_name']);
        }
        asort($final);
        return $final;
    }
}