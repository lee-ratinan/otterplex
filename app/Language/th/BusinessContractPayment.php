<?php
return [
    'field' => [
        'id'             => 'ไอดี',
        'contract_id'    => 'สัญญา',
        'amount_paid'    => 'จำนวนเงินที่จ่ายแล้ว',
        'payment_method' => 'วิธีการจ่ายเงิน',
        'payment_notes'  => 'บันทึกเกี่ยวกับธุรกรรม',
        'staff_comment'  => 'บันทึกของพนักงาน',
        'payment_status' => 'สถานะทางการเงิน',
    ],
    'enum'  => [
        'payment_method' => [
            'CASH'      => 'เงินสด',
            'TRANSFER'  => 'โอนเงินเข้าบัญชีธนาคาร',
            'PROMPTPAY' => 'พร้อมเพย์',
            'OTHER'     => 'อื่น'
        ],
        'payment_status' => [
            'COMPLETE' => 'จ่ายแล้ว',
            'FAIL'     => 'ล้มเหลว',
            'PENDING'  => 'ยังไม่ได้จ่าย',
        ]
    ]
];