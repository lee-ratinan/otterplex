<?php

namespace App\Models;

use App\Models\AppBaseModel;

class AllocationResourceModel extends AppBaseModel
{
    protected $table = 'allocation_resource';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'resource_id',
        'session_breakdown_id',
        'allocation_type',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function checkResourceConflict(int|array $resourceId, string $start, string $end): array|null
    {
        if (is_array($resourceId)) {
            $this->whereIn('resource_id', $resourceId);
        } else {
            $this->where('resource_id', $resourceId);
        }
        return $this->select('allocation_resource.*, session_breakdown.time_start, session_breakdown.time_end')
            ->join('session_breakdown', 'allocation_resource.session_breakdown_id = session_breakdown.id')
            ->groupStart()
            ->where('session_breakdown.time_start <', $end)
            ->where('session_breakdown.time_end >', $start)
            ->groupEnd()
            ->findAll();
    }
}