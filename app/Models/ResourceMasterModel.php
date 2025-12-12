<?php

namespace App\Models;

class ResourceMasterModel extends AppBaseModel
{
    protected $table = 'resource_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'branch_id',
        'resource_type_id',
        'resource_name',
        'resource_description',
        'is_active',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * @param string $search
     * @return void
     */
    private function applyFilters(string $search): void
    {
        $this->groupStart()
            ->like('resource_name', $search)
            ->orLike('resource_description', $search)
            ->orLike('branch_local_names', $search)
            ->orLike('resource_local_names', $search)
            ->groupEnd();
    }
    /**
     * Retrieve data for the table
     * @param int $draw
     * @param int $offset
     * @param int $length
     * @param string $search
     * @param int $orderBy
     * @param string $orderDir
     * @return array
     */
    public function getDataTable(int $draw, int $offset, int $length, string $search, int $orderBy, string $orderDir): array
    {
        $session    = session();
        $businessId = $session->business['id'];
        $columns    = [
            'resource_master.resource_name',
            'resource_master.resource_description',
            'branch_master.branch_name',
            'resource_type.resource_type',
            'resource_master.is_active',
            'resource_master.resource_name',
        ];
        $orderBy    = $columns[$orderBy] ?? $columns[0];
        $total      = $this->join('branch_master', 'branch_master.id = resource_master.branch_id')->where('branch_master.business_id', $businessId)->countAllResults();
        $filtered   = $total;
        if (!empty($search)) {
            $this->join('branch_master', 'branch_master.id = resource_master.branch_id')
                ->join('resource_type', 'resource_type.id = resource_master.resource_type_id');
            $this->applyFilters($search);
            $filtered = $this->countAllResults();
            $this->applyFilters($search);
        }
        $data       = $this->select('resource_master.*, branch_master.branch_name, branch_master.branch_local_names,
                resource_type.resource_type, resource_type.resource_local_names')
            ->join('branch_master', 'branch_master.id = resource_master.branch_id')
            ->join('resource_type', 'resource_type.id = resource_master.resource_type_id')
            ->where('branch_master.business_id', $businessId)
            ->orderBy($orderBy, $orderDir)
            ->limit($length, $offset)
            ->findAll();
        $final      = [];
        foreach ($data as $row) {
            $branch_names = json_decode($row['branch_local_names'], true);
            $type_names   = json_decode($row['resource_local_names'], true);
            $branch       = $branch_names[$session->lang] ?? $row['branch_name'];
            $type         = $type_names[$session->lang] ?? $row['resource_type'];
            $final[] = [
                $row['resource_name'],
                $row['resource_description'],
                $branch,
                $type,
                lang('ResourceMaster.enum.is_active.' . $row['is_active']),
                '<a class="btn btn-primary btn-sm float-end" href="' . base_url('admin/resource/' . ($row['id'] * ID_MASKED_PRIME)) . '">' . lang('System.buttons.edit') . '</a>'
            ];
        }
        return [
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $final,
        ];
    }
}