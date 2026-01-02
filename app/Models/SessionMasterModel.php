<?php

namespace App\Models;

class SessionMasterModel extends AppBaseModel
{
    protected $table = 'session_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'service_variant_id',
        'session_type',
        'session_capacity',
        'short_description',
        'date_start',
        'date_end',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAvailableSessions(string $variantSlug, string $languageCode, string $dateFrom, string $dateTo, int $branchId): array
    {
        $today    = date('Y-m-d');
        if (empty($dateFrom)) {
            $dateFrom = $today;
        }
        if (!empty($dateTo)) {
            $this->where('date_end <=', $dateTo);
        }
        if (0 < $branchId) {
            $this->where('session_master.branch_id', $branchId);
        }
        $sessions = $this->select('session_master.id, session_master.session_type, session_master.session_capacity, session_master.short_description, session_master.date_start, session_master.date_end, service_variant.variant_slug, branch_master.branch_name, branch_master.branch_local_names')
            ->join('service_variant', 'service_variant.id = session_master.service_variant_id')
            ->join('branch_master', 'session_master.branch_id = branch_master.id')
            ->where('date_start >=', $dateFrom)
            ->where('session_type', 'OPEN')
            ->where('variant_slug', $variantSlug)
            ->findAll();
        if (empty($sessions)) {
            return [];
        }
        $sIds     = [];
        foreach ($sessions as $session) {
            $sIds[] = $session['id'];
        }
        $sbdModel = new SessionBreakDownModel();
        $times    = $sbdModel->getSessions($sIds, $languageCode);
        $final    = [];
        foreach ($sessions as $session) {
            $branchNames            = json_decode($session['branch_local_names'], true);
            $session['branch_name'] = $branchNames[$languageCode] ?? $session['branch_name'];
            $session['link_id']     = $session['id'] * ID_MASKED_PRIME;
            unset($session['branch_local_names']);
            $final[$session['id']]             = $session;
            $final[$session['id']]['sessions'] = $times[$session['id']];
        }
        return $final;
    }
}