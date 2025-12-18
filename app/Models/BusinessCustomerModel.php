<?php

namespace App\Models;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class BusinessCustomerModel extends AppBaseModel
{
    protected $table = 'business_customer';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'customer_id',
        'customer_code',
        'customer_status',
        'marketing_opt_in',
        'first_seen_at',
        'last_seen_at',
        'staff_comment',
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
            ->like('customer_master.email_address', $search)
            ->orLike('customer_master.telephone_number', $search)
            ->orLike('customer_master.customer_name', $search)
            ->orLike('customer_address.address_city', $search)
            ->groupEnd();
    }

    /**
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
        $session = session();
        $columns = [
            'customer_master.email_address',
            'customer_master.telephone_number',
            'customer_master.customer_name',
            'customer_master.is_active',
            'customer_address.address_city',
            'customer_address.country_code',
        ];
        $orderBy    = $columns[$orderBy] ?? $columns[0];
        $businessId = $session->business['business_id'];
        $total      = $this->where('business_id', $businessId)->countAllResults();
        $filtered   = $total;
        if (!empty($search)) {
            $this->join('customer_master', 'customer_master.id = business_customer.customer_id')
                ->join('customer_address', 'customer_address.customer_id = customer_master.id', 'left outer');
            $this->applyFilters($search);
            $filtered = $this->countAllResults();
            $this->applyFilters($search);
        }
        $customers = $this->select('business_customer.*, customer_master.email_address, customer_master.telephone_number, customer_master.customer_name, customer_master.is_active, customer_address.address_city, customer_address.country_code')
            ->join('customer_master', 'customer_master.id = business_customer.customer_id')
            ->join('customer_address', 'customer_address.customer_id = customer_master.id', 'left outer')
            ->where('business_id', $businessId)
            ->orderBy($orderBy, $orderDir)
            ->limit($length, $offset)
            ->findAll();
        $countries  = get_country_codes()['countries'];
        $final      = [];
        $phone_util = PhoneNumberUtil::getInstance();
        foreach ($customers as $customer) {
            try {
                $phone_obj    = $phone_util->parse($customer['telephone_number'], @$customer['country_code']);
                $phone_number = $phone_util->format($phone_obj, PhoneNumberFormat::INTERNATIONAL);
            } catch (\Exception $e) {
                $phone_number = $customer['telephone_number'];
            }
            $final[]   = [
                $customer['email_address'],
                $phone_number,
                $customer['customer_name'],
                lang('CustomerMaster.enum.is_active.' . $customer['is_active']),
                $customer['address_city'],
                @$countries[$customer['country_code']]['common_name'],
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