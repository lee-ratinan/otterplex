<?php

namespace App\Models;

class BranchMasterModel extends AppBaseModel
{
    protected $table = 'branch_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'subdivision_code',
        'branch_name',
        'branch_slug',
        'branch_local_names',
        'timezone_code',
        'branch_type',
        'branch_address',
        'branch_postal_code',
        'branch_status',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * @param int $draw
     * @param int $start
     * @param int $length
     * @return array
     */
    public function getDataTable(int $draw, int $start, int $length): array
    {
        $session    = session();
        $businessId = $session->business['id'];
        $total      = $this->where('business_id', $businessId)->countAllResults();
        $branches   = $this->where('business_id', $businessId)
            ->limit($length, $start)
            ->find();
        $data       = [];
        // external data
        $countryCode  = $session->business['country_code'];
        $subdivisions = get_country_codes()['subdivisions'][$countryCode];
        foreach ($branches as $branch) {
            $data[] = [
                $subdivisions[$branch['subdivision_code']],
                $branch['branch_name'],
                get_tzdb($branch['timezone_code'])['label'],
                lang('BranchMaster.enum.branch_type.' . $branch['branch_type']),
                lang('BranchMaster.enum.branch_status.' . $branch['branch_status']),
                '<a href="' . base_url('admin/business/branch/' . $branch['branch_slug']) . '">' . lang('System.buttons.view-more') . '</a>'
            ];
        }
        return [
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data,
        ];
    }
}