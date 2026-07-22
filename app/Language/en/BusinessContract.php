<?php
return [
    'field' => [
        'id'               => 'ID',
        'business_id'      => 'Business Name',
        'issued_date'      => 'Issued Date',
        'invoice_number'   => 'Invoice Number',
        'plan_country'     => 'Country of Jurisdiction',
        'plan_name'        => 'Plan Name',
        'plan_duration'    => 'Plan Duration',
        'contract_start'   => 'Start Date',
        'contract_expiry'  => 'Expiry Date',
        'currency_code'    => 'Currency',
        'invoiced_amount'  => 'Invoiced Amount',
        'discount_amount'  => 'Discount Amount',
        'tax_amount'       => 'Tax Amount',
        'total_amount'     => 'Total Amount',
        'paid_amount'      => 'Paid Amount',
        'financial_status' => 'Financial Status',
    ],
    'enum'  => [
        'plan_name'        => [
            'basic'    => 'Basic',
            'standard' => 'Standard',
            'premium'  => 'Premium',
        ],
        'plan_duration'    => [
            'monthly'  => '1 month',
            'annually' => '1 year',
        ],
        'financial_status' => [
            'PENDING'  => 'Pending',
            'PAID'     => 'Paid',
            'REFUNDED' => 'Refunded',
            'CANCELED' => 'Cancelled',
        ]
    ]
];