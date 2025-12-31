<?php

namespace App\Models;

use App\Models\AppBaseModel;

class SessionBreakDownModel extends AppBaseModel
{
    protected $table = 'session_breakdown';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'session_id',
        'time_start',
        'time_end',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getSessions(int|array $sessionIds): array
    {
        if (empty($sessionIds)) {
            return [];
        }
        if (is_int($sessionIds)) {
            $sessionIds = [$sessionIds];
        }
        $sessions = $this->select('session_breakdown.id, session_breakdown.session_id, session_breakdown.time_start, session_breakdown.time_end, '.
            'allocation_staff.user_id, allocation_resource.resource_id, user_master.user_name_first, resource_master.resource_name')
            ->join('allocation_staff', 'allocation_staff.session_breakdown_id = session_breakdown.id', 'left outer')
            ->join('user_master', 'allocation_staff.user_id = user_master.id', 'left outer')
            ->join('allocation_resource', 'allocation_resource.session_breakdown_id = session_breakdown.id', 'left outer')
            ->join('resource_master', 'allocation_resource.resource_id = resource_master.id', 'left outer')
            ->whereIn('session_id', $sessionIds)
            ->orderBy('time_start', 'ASC')
            ->findAll();
        $results = [];
        foreach ($sessions as $session) {
            $results[$session['session_id']][] = $session;
        }
        return $results;
    }
}