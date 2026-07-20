<?php

namespace App\Models;

class RunningNumberModel extends AppBaseModel
{
    protected $table = 'running_number';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'number_type',
        'number_group',
        'latest_number',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * @return string[]
     */
    public function getNumberType(): array
    {
        return ['otter_invoice'];
    }

    /**
     * @param string $number_type
     * @param int $number_group
     * @return int
     */
    public function getNewRunningNumber(string $number_type, int $number_group): int
    {
        try {
            $available_types = $this->getNumberType();
            if (!in_array($number_type, $available_types)) {
                return 0;
            }
            $row = $this->where(['number_type' => $number_type, 'number_group' => $number_group])->first();
            if ($row) {
                $latest_number = $row['latest_number'] + 1;
                $this->update($row['id'], ['latest_number' => $latest_number]);
                return $latest_number;
            }
            $latest_number = 1001;
            $data          = [
                'number_type'   => $number_type,
                'number_group'  => $number_group,
                'latest_number' => $latest_number
            ];
            $this->insert($data);
            return $latest_number;
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return 0;
        }
    }

    /**
     * @param string $country_code
     * @param int $ad_year
     * @param int $month
     * @return string
     */
    public function generateOtterInvoiceNumber(string $country_code, int $ad_year, int $month): string
    {
        $reiwa_year = $ad_year - 2018;
        $reiwa_year = str_pad($reiwa_year, 2, '0', STR_PAD_LEFT);
        $month      = str_pad($month, 2, '0', STR_PAD_LEFT);
        $next       = $this->getNewRunningNumber('otter_invoice', $ad_year);
        $next       = str_pad($next, 6, '0', STR_PAD_LEFT);
        return strtoupper($country_code) . $reiwa_year . $month . $next;
    }
}