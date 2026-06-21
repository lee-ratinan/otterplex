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
        'google_map_url',
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
     * @return array
     */
    public function getDataTable(): array
    {
        $session    = session();
        $businessId = $session->business['business_id'];
        $branches   = $this->where('business_id', $businessId)
            ->orderBy('branch_name', 'ASC')
            ->find();
        $data       = [];
        // external data
        $countryCode  = $session->business['country_code'];
        $subdivisions = get_country_subdivisions($countryCode);
        foreach ($branches as $branch) {
            $data[] = [
                $subdivisions[$branch['subdivision_code']],
                $branch['branch_name'],
                get_tzdb_by_code($branch['timezone_code']),
                lang('BranchMaster.enum.branch_type.' . $branch['branch_type']),
                lang('BranchMaster.enum.branch_status.' . $branch['branch_status']),
                '<a class="btn btn-primary btn-sm float-end" href="' . base_url('admin/business/branch/' . $branch['branch_slug']) . '">' . lang('System.buttons.edit') . '</a>'
            ];
        }
        return [
            'data' => $data,
        ];
    }

    public function findHours(int $branchId, string $date): array
    {
        $modModel = new BranchModifiedHoursModel();
        $hrsModel = new BranchOpeningHoursModel();
        $dows     = [
            'SU', 'M', 'T', 'W', 'TH', 'F', 'S',
        ];
        $dow     = $dows[date('w', strtotime($date))];
        $date    = $modModel->where('branch_id', $branchId)->where('modified_hours_date', $date)->first();
        $hours   = $hrsModel->where('branch_id', $branchId)->where('day_of_the_week', $dow)->first();
        $result  = [];
        if (!empty($date)) {
            if ('CLOSED' == $date['modified_type']) {
                return ['', ''];
            } else {
                return [$date['updated_opening_hours'], $date['updated_closing_hours']];
            }
        }
        if (!empty($hours)) {
            return [$hours['opening_hours'], $hours['closing_hours']];
        }
        return ['', ''];
    }

    private function formatResultBranchInfoAndHours(array $branch, string $date, array $hours): array
    {
        try {
            $tzUTC    = new \DateTimeZone('UTC');
            $now      = new \DateTime('now', $tzUTC);
            $result   = [
                'id'                 => $branch['id'],
                'branch_name'        => $branch['branch_name'],
                'branch_local_names' => $branch['branch_local_names'],
                'timezone_code'      => $branch['timezone_code'],
                'branch_type'        => $branch['branch_type'],
                'branch_status'      => $branch['branch_status'],
            ];
            $timezone = $branch['timezone_code'];
            $tzLocal  = new \DateTimeZone($timezone);
            if (!empty($hours[0]) && !empty($hours[1])) {
                $openingTime = $date . ' ' . $hours[0];
                $closingTime = $date . ' ' . $hours[1];
                $open        = new \DateTime($openingTime, $tzLocal);
                $close       = new \DateTime($closingTime, $tzLocal);
                $open->setTimezone($tzUTC);
                $close->setTimezone($tzUTC);
                if ($open < $now) {
                    unset($open);
                    $open = clone $now;
                    $timeMinute = intval($open->format('i'));
                    if (30 > $timeMinute) {
                        $open->setTime((int)$open->format('H'), 30, 0);
                    } else {
                        $open->modify('+1 hour');
                        $open->setTime((int)$open->format('H'), 0, 0);
                    }
                }
                $result['opening_hours'] = [
                    $open->format('Y-m-d\TH:i:s') . '+00:00',
                    $close->format('Y-m-d\TH:i:s') . '+00:00',
                ];
            } else {
                $result['opening_hours']  = ['', ''];
            }
            return $result;
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return [];
        }
    }

    public function findBranchInfoAndHoursByBranch(int $branchId, string $date): array
    {
        $branch = $this->findRow($branchId);
        if (empty($branch)) {
            return [];
        }
        $branch['branch_local_names'] = json_decode($branch['branch_local_names'], true);
        $hours                        = $this->findHours($branchId, $date);
        return $this->formatResultBranchInfoAndHours($branch, $date, $hours);
    }
}