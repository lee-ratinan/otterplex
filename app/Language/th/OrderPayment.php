<?php
return [
    'field' => [
        'order_id'       => 'ออร์เดอร์',
        'amount_paid'    => 'จำนวนเงินที่จ่าย',
        'payment_method' => 'วิธีการชำระเงิน',
        'payment_notes'  => 'รายละเอียดการชำระเงิน',
        'staff_comment'  => 'คอมเมนต์พนักงาน',
        'payment_status' => 'สถานะการชำระเงิน',
    ],
    'enum'  => [
        'payment_status' => [
            'COMPLETE' => 'ชำระแล้ว',
            'FAIL'     => 'ล้มเหลว',
            'PENDING'  => 'ยังไม่ชำระ',
        ]
    ]
];