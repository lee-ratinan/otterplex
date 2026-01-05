<?php
return [
    'field' => [
        'id'                  => 'ไอดี',
        'business_id'         => 'ธุรกิจ',
        'payment_method'      => 'วิธีการชำระเงิน',
        'payment_instruction' => 'รายละเอียดการชำระเงิน',
        'payment_instructions' => [
            'instruction'    => 'วิธีการ',
            'swift_code'     => 'รหัส SWIFT',
            'account_name'   => 'ชื่อบัญชี',
            'account_number' => 'เลขที่บัญชี',
            'type'           => 'ประเภท',
            'id_types'       => [
                'phone'  => 'หมายเลขโทรศัพท์มือถือ'
            ],
            'target_value'   => 'หมายเลขโทรศัพท์มือถือ',
            'title'          => 'ช่องทาง'
        ]
    ],
    'enum'  => [
        'payment_method' => [
            'cash_on_delivery' => 'เงินสด (สำหรับบริการจัดส่ง)',
            'cash'             => 'เงินสด',
            'bank_transfer'    => 'โอนเงินผ่านธนาคาร',
            'promptpay_static' => 'พร้อมเพย์ QR (ระบบคิวอาร์เดียว)',
            'paypal_me'        => 'PayPal Me',
            'external_online'  => 'จ่ายเงินออนไลน์ (ระบบอื่น)'
        ]
    ],
    'methods' => [
        'cash'             => 'เงินสด',
        'bank_transfer'    => 'โอนเงินผ่านธนาคาร',
        'promptpay_static' => 'พร้อมเพย์ QR (ระบบคิวอาร์เดียว)',
        'external_online'  => 'จ่ายเงินออนไลน์ (ระบบอื่น)'
    ]
];