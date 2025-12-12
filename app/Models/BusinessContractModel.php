<?php

namespace App\Models;

class BusinessContractModel extends AppBaseModel
{
    protected $table = 'business_contract';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'package_id',
        'invoice_number',
        'contract_start',
        'contract_expiry',
        'invoiced_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'financial_status',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    const string FINANCIAL_STATUS_PENDING = 'PENDING';
    const string FINANCIAL_STATUS_PAID = 'PAID';
    const string FINANCIAL_STATUS_REFUNDED = 'REFUNDED';
    const string FINANCIAL_STATUS_CANCELED = 'CANCELED';

    /**
     * Get contracts and their payment data
     * @param int $businessId
     * @return array
     */
    public function retrieveDataByBusinessId(int $businessId): array
    {
        $paymentModel = new BusinessContractPaymentModel();
        $allContracts = $this->select('business_contract.*, otternaut_package.country_code, otternaut_package.package_name')
            ->join('otternaut_package', 'otternaut_package.id = business_contract.package_id')
            ->where('business_id', $businessId)->orderBy('contract_start', 'DESC')->limit(15)->findAll();
        $contractIds  = [];
        $final        = [];
        foreach ($allContracts as $contract) {
            $contractIds[]          = $contract['id'];
            $final[$contract['id']] = $contract;
        }
        if (!empty($contractIds)) {
            $payments = $paymentModel->whereIn('contract_id', $contractIds)->findAll();
            foreach ($payments as $payment) {
                $final[$payment['contract_id']]['payments'][] = $payment;
            }
        }
        return $final;
    }
}