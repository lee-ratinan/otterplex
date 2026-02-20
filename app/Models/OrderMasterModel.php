<?php

namespace App\Models;

use App\Models\AppBaseModel;

class OrderMasterModel extends AppBaseModel
{
    protected $table         = 'order_master';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'customer_id',
        'customer_address_id',
        'order_number',
        'order_subtotal',
        'order_adjustment',
        'order_total',
        'shipping_option',
        'payment_method',
        'collection_branch_id',
        'order_status',
        'financial_status',
        'shipping_status',
        'staff_comment',
        'customer_comment',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function applyFilters(string $search, string $shippingOption, string $paymentMethod, string $orderStatus, string $financialStatus, string $shippingStatus): void
    {
        if (!empty($search)) {
            $this->where('order_number', $search);
        }
        if (!empty($shippingStatus)) {
            $this->where('shipping_option', $shippingOption);
        }
        if (!empty($paymentMethod)) {
            $this->where('payment_method', $paymentMethod);
        }
        if (!empty($orderStatus)) {
            $this->where('order_status', $orderStatus);
        }
        if (!empty($financialStatus)) {
            $this->where('financial_status', $financialStatus);
        }
        if (!empty($shippingStatus)) {
            $this->where('shipping_status', $shippingStatus);
        }
    }

    public function getDataTable(int $draw, int $offset, int $length, string $search, int $orderBy, string $orderDir,
                                 string $shippingOption, string $paymentMethod, string $orderStatus, string $financialStatus, string $shippingStatus): array
    {
        $session    = session();
        $businessId = $session->business['business_id'];
        $currency   = $session->business['currency_code'];
        $columns    = [
            'order_master.order_number',
            'customer_master.customer_name',
            'order_master.order_total',
            'order_master.shipping_option',
            'order_master.payment_method',
            'order_master.order_status',
            'order_master.financial_status',
            'order_master.shipping_status',
            'order_master.id'
        ];
        $orderBy    = $columns[$orderBy] ?? $columns[0];
        $total      = $this->where('business_id', $businessId)->countAllResults();
        $filtered   = $total;
        if (!empty($search)) {
            $this->applyFilters($search, $shippingOption, $paymentMethod, $orderStatus, $financialStatus, $shippingStatus);
            $filtered = $this->countAllResults();
            $this->applyFilters($search, $shippingOption, $paymentMethod, $orderStatus, $financialStatus, $shippingStatus);
        }
        $data       = $this->select('order_master.*, customer_master.customer_name')
            ->join('customer_master', 'order_master.customer_id = customer_master.id')
            ->where('order_master.business_id', $businessId)
            ->orderBy($orderBy, $orderDir)
            ->limit($length, $offset)
            ->findAll();
        $final      = [];
        foreach ($data as $row) {
            $paymentMethod = $row['payment_method'];
            if (in_array($paymentMethod, ['cash', 'bank_transfer', 'promptpay_static', 'external_online'])) {
                $paymentMethod = lang('BusinessPaymentMethod.methods.' . $paymentMethod);
            }
            $final[] = [
                $row['order_number'],
                $row['customer_name'],
                format_price($row['order_total'], $currency),
                lang('OrderMaster.enum.shipping_option.' . $row['shipping_option']),
                $paymentMethod,
                lang('OrderMaster.enum.order_status.' . $row['order_status']),
                lang('OrderMaster.enum.financial_status.' . $row['financial_status']),
                lang('OrderMaster.enum.shipping_status.' . $row['shipping_status']),
                '<a class="btn btn-primary btn-sm float-end" href="' . base_url('admin/order/' . ($row['id'] * ID_MASKED_PRIME)) . '">' . lang('System.buttons.edit') . '</a>'
            ];
        }
        return [
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $final,
        ];
    }

    public function getOrderInfo(int $orderId): array
    {
        $orderInfo = $this->findRow($orderId);
        if (empty($orderInfo)) {
            return [];
        }
        // Models
        $lineItemModel     = new OrderLineItemModel();
        $orderBookingModel = new OrderBookingItemModel();
        $lineAdjModel      = new OrderLineAdjustmentModel();
        $paymentModel      = new OrderPaymentModel();
        // Details
        $orderInfo['line_items']    = $lineItemModel->where('order_id', $orderId)->findAll();
        $orderInfo['booking_items'] = $orderBookingModel->where('order_id', $orderId)->findAll();
        $orderInfo['adjustments']   = $lineAdjModel->where('order_id', $orderId)->findAll();
        $orderInfo['payments']      = $paymentModel->where('order_id', $orderId)->findAll();
        return $orderInfo;
    }
}