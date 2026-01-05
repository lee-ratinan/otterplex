<?php
return [
    'field'   => [
        'id'                   => 'ID',
        'business_id'          => 'Business',
        'payment_method'       => 'Payment Method',
        'payment_instruction'  => 'Payment Instruction',
        'payment_instructions' => [
            'instruction'    => 'Payment Instruction',
            'swift_code'     => 'SWIFT Code',
            'account_name'   => 'Account Name',
            'account_number' => 'Account Number',
            'type'           => 'Type',
            'id_types'       => [
                'phone'  => 'Phone',
                'id_num' => 'ID Number'
            ],
            'target_value'   => 'Phone Number / ID Number',
            'title'          => 'Channel'
        ]
    ],
    'enum'    => [
        'payment_method' => [
            'cash_on_delivery' => 'Cash-on-delivery (for delivery of products only)',
            'cash'             => 'Cash',
            'bank_transfer'    => 'Bank Transfer',
            'promptpay_static' => 'PromptPay QR (Static Code)',
            'paypal_me'        => 'PayPal Me',
            'external_online'  => 'Online Payment (External)'
        ]
    ],
    'methods' => [
        'cash'             => 'Cash',
        'bank_transfer'    => 'Bank Transfer',
        'promptpay_static' => 'PromptPay QR (Static Code)',
        'external_online'  => 'Online Payment (External)'
    ]
];