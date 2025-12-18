<?php

namespace App\Models;

class ResourceTypeModel extends AppBaseModel
{
    protected $table = 'resource_type';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'resource_type',
        'resource_local_names',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Retrieve data for the table
     * @return array
     */
    public function getDataTable(): array
    {
        $session    = session();
        $businessId = $session->business['business_id'];
        $data       = $this
            ->where('business_id', $businessId)
            ->orderBy('resource_type', 'ASC')
            ->findAll();
        $final      = [];
        foreach ($data as $row) {
            $locals  = json_decode($row['resource_local_names'], true);
            $name    = $locals[$session->lang] ?? $row['resource_type'];
            $final[] = [
                $name,
                '<a class="btn btn-sm btn-primary float-end" href="' . base_url('admin/resource/type/' . ($row['id'] * ID_MASKED_PRIME)) . '">' . lang('System.buttons.edit') . '</a>'
            ];
        }
        return [
            'data' => $final,
        ];
    }
}