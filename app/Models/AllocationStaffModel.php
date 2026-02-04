<?php

namespace App\Models;

use App\Models\AppBaseModel;

class AllocationStaffModel extends AppBaseModel
{
    protected $table = 'allocation_staff';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'user_id',
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

    public function checkStaffConflict(int $userId, string $newStart, string $newEnd): array|null
    {
        return $this->select('allocation_staff.*, session_breakdown.time_start, session_breakdown.time_end')
            ->join('session_breakdown', 'allocation_staff.session_breakdown_id = session_breakdown.id')
            ->where('user_id', $userId)
            ->groupStart()
            ->where('session_breakdown.time_start <', $newEnd)
            ->where('session_breakdown.time_end >', $newStart)
            ->groupEnd()
            ->findAll();
    }
}