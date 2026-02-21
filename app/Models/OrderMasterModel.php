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

    public function getStatusIcons(): array
    {
        return [
            'shipping_option'  => [
                'NOT_APPLICABLE'  => '<i class="fa-solid fa-circle-minus text-muted"></i> ',
                'SELF_COLLECTION' => '<i class="fa-solid fa-hand-holding"></i> ',
                'SHIPPING'        => '<i class="fa-solid fa-truck-fast"></i> '
            ],
            'order_status'     => [
                'OPEN'     => '<i class="fa-solid fa-circle-dot text-success"></i> ',
                'CLOSED'   => '<i class="fa-solid fa-circle-check text-muted"></i> ',
                'CANCELED' => '<i class="fa-solid fa-circle-xmark text-danger"></i> '
            ],
            'financial_status' => [
                'PENDING'            => '<i class="fa-solid fa-circle-dot text-muted"></i> ',
                'PAID'               => '<i class="fa-solid fa-circle-check text-success"></i> ',
                'PARTIALLY_PAID'     => '<i class="fa-solid fa-circle-minus text-warning"></i> ',
                'REFUNDED'           => '<i class="fa-solid fa-circle-check text-muted"></i> ',
                'PARTIALLY_REFUNDED' => '<i class="fa-solid fa-circle-minus text-muted"></i> '
            ],
            'shipping_status'  => [
                'OPEN'           => '<i class="fa-solid fa-circle text-danger"></i> ',
                'IN_PROGRESS'    => '<i class="fa-solid fa-circle-dot text-success"></i> ',
                'SHIPPED'        => '<i class="fa-solid fa-circle-check text-success"></i> ',
                'RETURNED'       => '<i class="fa-solid fa-circle-xmark text-danger"></i> ',
                'NOT_APPLICABLE' => '<i class="fa-solid fa-circle-minus text-muted"></i> '
            ],
        ];
    }

    public function applyFilters(string $search, string $shippingOption, string $paymentMethod, string $orderStatus, string $financialStatus, string $shippingStatus): void
    {
        if (!empty($search)) {
            $this->where('order_number', $search);
        }
        if (!empty($shippingOption)) {
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
        if (!empty($search) || !empty($shippingOption) || !empty($paymentMethod) || !empty($orderStatus) || !empty($financialStatus) || !empty($shippingStatus)) {
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
        $statuses   = $this->getStatusIcons();
        foreach ($data as $row) {
            $paymentMethod = $row['payment_method'];
            if (in_array($paymentMethod, ['cash', 'bank_transfer', 'promptpay_static', 'external_online'])) {
                $paymentMethod = lang('BusinessPaymentMethod.methods.' . $paymentMethod);
            }
            $final[] = [
                $row['order_number'],
                $row['customer_name'],
                format_price($row['order_total'], $currency),
                $statuses['shipping_option'][$row['shipping_option']] . lang('OrderMaster.enum.shipping_option.' . $row['shipping_option']),
                $paymentMethod,
                $statuses['order_status'][$row['order_status']] . lang('OrderMaster.enum.order_status.' . $row['order_status']),
                $statuses['financial_status'][$row['financial_status']] . lang('OrderMaster.enum.financial_status.' . $row['financial_status']),
                $statuses['shipping_status'][$row['shipping_status']] . lang('OrderMaster.enum.shipping_status.' . $row['shipping_status']),
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
        $orderInfo = $this->select('order_master.*,
            customer_master.customer_name, customer_master.email_address, customer_master.telephone_number,
            customer_address.address_line_1, customer_address.address_line_2, customer_address.address_line_3,
            customer_address.address_city, customer_address.country_code, customer_address.postal_code,
            branch_master.branch_name, branch_master.branch_local_names')
            ->join('customer_master', 'order_master.customer_id = customer_master.id', 'left outer')
            ->join('customer_address', 'order_master.customer_address_id = customer_address.id', 'left outer')
            ->join('branch_master', 'order_master.collection_branch_id = branch_master.id', 'left outer')
            ->where('order_master.id', $orderId)
            ->first();
        if (empty($orderInfo)) {
            return [];
        }
        // Models
        $lineItemModel     = new OrderLineItemModel();
        $orderBookingModel = new OrderBookingItemModel();
        $lineAdjModel      = new OrderLineAdjustmentModel();
        $paymentModel      = new OrderPaymentModel();
        // Details
        $orderInfo['line_items']    = $lineItemModel->getLineItemsByOrderId($orderId);
        $orderInfo['booking_items'] = $orderBookingModel->getBookingItemsByOrderId($orderId);
        $orderInfo['adjustments']   = $lineAdjModel->where('order_id', $orderId)->findAll();
        $orderInfo['payments']      = $paymentModel->where('order_id', $orderId)->findAll();
        return $orderInfo;
    }
}