<?php
return [
    'field' => [
        'business_id'          => 'Business',
        'customer_id'          => 'Customer',
        'customer_address_id'  => 'Customer Address',
        'order_number'         => 'Order Number',
        'order_subtotal'       => 'Subtotal',
        'order_adjustment'     => 'Adjustment',
        'order_total'          => 'Total',
        'shipping_option'      => 'Shipping Option',
        'payment_method'       => 'Payment Method',
        'collection_branch_id' => 'Collection Branch',
        'order_status'         => 'Order Status',
        'financial_status'     => 'Financial Status',
        'shipping_status'      => 'Shipping Status',
        'staff_comment'        => 'Staff Comment',
        'customer_comment'     => 'Customer Comment',
    ],
    'enum'  => [
        'shipping_option'  => [
            'NOT_APPLICABLE'  => 'Not Applicable',
            'SELF_COLLECTION' => 'Self-Collection',
            'SHIPPING'        => 'Shipping',
        ],
        'order_status'     => [
            'OPEN'     => 'Open',
            'CLOSED'   => 'Closed',
            'CANCELED' => 'Canceled',
        ],
        'financial_status' => [
            'PENDING'            => 'Pending',
            'PAID'               => 'Paid',
            'PARTIALLY_PAID'     => 'Partially Paid',
            'REFUNDED'           => 'Refunded',
            'PARTIALLY_REFUNDED' => 'Partially Refunded',
        ],
        'shipping_status'  => [
            'OPEN'           => 'Open',
            'IN_PROGRESS'    => 'In Progress',
            'SHIPPED'        => 'Shipped',
            'RETURNED'       => 'Returned',
            'NOT_APPLICABLE' => 'Not Applicable',
        ],
    ]
];