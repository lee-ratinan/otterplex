<?php
return [
    'field' => [
        'order_id'       => 'Order',
        'amount_paid'    => 'Paid Amount',
        'payment_method' => 'Payment Method',
        'payment_notes'  => 'Payment Notes',
        'staff_comment'  => 'Staff Comment',
        'payment_status' => 'Payment Status',
    ],
    'enum'  => [
        'payment_status' => [
            'COMPLETE' => 'Completed',
            'FAIL'     => 'Failed',
            'PENDING'  => 'Pending',
        ]
    ]
];