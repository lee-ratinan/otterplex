<?php
return [
    'field' => [
        'id'             => 'ID',
        'contract_id'    => 'Contract',
        'amount_paid'    => 'Amount',
        'payment_method' => 'Payment Method',
        'payment_notes'  => 'Payment Notes',
        'staff_comment'  => 'Staff Comment',
        'payment_status' => 'Payment Status',
    ],
    'enum'  => [
        'payment_method' => [
            'CASH'      => 'Cash',
            'TRANSFER'  => 'Wire Transfer',
            'PROMPTPAY' => 'PromptPay',
            'OTHER'     => 'Others'
        ],
        'payment_status' => [
            'COMPLETE' => 'Completed',
            'FAIL'     => 'Failed',
            'PENDING'  => 'Pending',
        ]
    ]
];