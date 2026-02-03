<?php

namespace App\Models;

class SessionMasterModel extends AppBaseModel
{
    protected $table = 'session_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'branch_id',
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

    private function applyFilters(string $date_start, string $date_end, int $branch_id)
    {
        if (!empty($date_start)) {
            $this->where('date_start >=', $date_start);
        }
        if (!empty($date_end)) {
            $this->where('date_end <=', $date_end);
        }
        if (0 < $branch_id) {
            $this->where('branch_id', $branch_id);
        }
    }

    public function getDatatable(int $draw, int $start, int $length, int $service_id, int $service_variant_id, string $date_start, string $date_end, int $branch_id): array
    {
        $session    = session();
        $lang       = $session->lang;
        $total      = $this->where('service_variant_id', $service_variant_id)->countAllResults();
        $filtered   = $total;
        if (!empty($date_start) || !empty($date_end) || !empty($branch_id)) {
            $this->applyFilters($date_start, $date_end, $branch_id);
            $filtered = $this->where('service_variant_id', $service_variant_id)->countAllResults();
            $this->applyFilters($date_start, $date_end, $branch_id);
        }
        $data  = $this->select('session_master.*, branch_master.branch_name, branch_master.branch_local_names')
            ->join('branch_master', 'branch_master.id = session_master.branch_id')
            ->where('service_variant_id', $service_variant_id)
            ->limit($length, $start)
            ->findAll();
        $final   = [];
        $url_ids = ($service_id * ID_MASKED_PRIME) . '/' . ($service_variant_id * ID_MASKED_PRIME);
        foreach ($data as $row) {
            $branch_names = json_decode($row['branch_local_names'], true);
            $branch_name  = $branch_names[$lang] ?? $row['branch_name'];
            $final[] = [
                $branch_name,
                $row['short_description'],
                $row['session_capacity'],
                (empty($row['date_start']) ? '-' : format_date($row['date_start'], $lang)),
                (empty($row['date_end']) ? '-' : format_date($row['date_end'], $lang)),
                '<a class="btn btn-primary btn-sm float-end" href="http://localhost:8100/admin/service/variant/session/' . $url_ids . '/' . ($row['id'] * ID_MASKED_PRIME) . '">' . lang('System.buttons.edit') . '</a>',
            ];
        }
        return [
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $final
        ];
    }

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