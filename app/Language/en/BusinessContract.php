<?php
return [
    'field' => [
        'id'               => 'ID',
        'business_id'      => 'Business Name',
        'package_id'       => 'Package',
        'invoice_number'   => 'Invoice Number',
        'contract_start'   => 'Start Date',
        'contract_expiry'  => 'Expiry Date',
        'invoiced_amount'  => 'Invoiced Amount',
        'discount_amount'  => 'Discount Amount',
        'total_amount'     => 'Total Amount',
        'paid_amount'      => 'Paid Amount',
        'financial_status' => 'Financial Status',
    ],
    'enum'  => [
        'financial_status' => [
            'PENDING'  => 'Pending',
            'PAID'     => 'Paid',
            'REFUNDED' => 'Refunded',
            'CANCELED' => 'Cancelled',
        ]
    ]
];