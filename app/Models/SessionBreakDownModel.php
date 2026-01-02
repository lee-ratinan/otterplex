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

    public function getSessions(int|array $sessionIds, string $languageCode): array
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
            // start
            $duration_str = '';
            $start_pieces = explode(' ', $session['time_start']);
            $start_date   = $start_pieces[0];
            $start_time   = $start_pieces[1];
            // end
            $end_pieces   = explode(' ', $session['time_end']);
            $end_date     = $end_pieces[0];
            $end_time     = $end_pieces[1];
            if ($start_date == $end_date) {
                $duration_str = format_date($start_date, $languageCode) . ': ' . format_time($start_time, $languageCode) . ' - ' . format_time($end_time, $languageCode);
            } else {
                $duration_str = format_date_time($session['time_start'], $languageCode) . ' - ' . format_date_time($session['time_end'], $languageCode);
            }
            $session['duration_str']           = $duration_str;
            $results[$session['session_id']][] = $session;
        }
        return $results;
    }
}