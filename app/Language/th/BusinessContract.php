<?php
return [
    'field' => [
        'id'               => 'ไอดี',
        'business_id'      => 'ชื่อธุรกิจ',
        'issued_date'      => 'วันที่ออก',
        'invoice_number'   => 'หมายเลขใบแจ้งหนี้',
        'plan_country'     => 'ประเทศ',
        'plan_name'        => 'ชื่อแผน',
        'plan_duration'    => 'ระยะเวลา',
        'contract_start'   => 'วันเริ่มต้นสัญญา',
        'contract_expiry'  => 'วันหมดอายุสัญญา',
        'currency_code'    => 'สกุลเงิน',
        'invoiced_amount'  => 'ราคาที่เรียกเก็บ',
        'discount_amount'  => 'ส่วนลด',
        'tax_amount'       => 'ภาษี',
        'total_amount'     => 'ราคาทั้งหมด',
        'paid_amount'      => 'จำนวนเงินที่จ่ายแล้ว',
        'financial_status' => 'สถานะทางการเงิน',
    ],
    'enum'  => [
        'plan_name'        => [
            'basic'    => 'เบสิก',
            'standard' => 'สแตนดาร์ด',
            'premium'  => 'พรีเมียม',
        ],
        'plan_duration'    => [
            'monthly'  => '1 เดือน',
            'annually' => '1 ปี',
        ],
        'financial_status' => [
            'PENDING'  => 'ยังไม่ได้จ่าย',
            'PAID'     => 'จ่ายแล้ว',
            'REFUNDED' => 'คืนเงินแล้ว',
            'CANCELED' => 'ยกเลิก'
        ]
    ]
];