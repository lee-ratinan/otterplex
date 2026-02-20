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