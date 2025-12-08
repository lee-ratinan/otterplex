<?php
return [
    'field' => [
        'id'               => 'ไอดี',
        'business_id'      => 'ชื่อธุรกิจ',
        'package_id'       => 'แพ็กเกจ',
        'invoice_number'   => 'หมายเลขใบแจ้งราคา',
        'contract_start'   => 'วันที่เริ่ม',
        'contract_expiry'  => 'วันหมดอายุ',
        'invoiced_amount'  => 'ราคาที่เรียกเก็บ',
        'discount_amount'  => 'ส่วนลด',
        'total_amount'     => 'ราคาทั้งหมด',
        'paid_amount'      => 'จำนวนเงินที่จ่ายแล้ว',
        'financial_status' => 'สถานะทางการเงิน',
    ],
    'enum'  => [
        'financial_status' => [
            'PENDING'  => 'ยังไม่ได้จ่าย',
            'PAID'     => 'จ่ายแล้ว',
            'REFUNDED' => 'คืนเงินแล้ว',
            'CANCELED' => 'ยกเลิก'
        ]
    ]
];