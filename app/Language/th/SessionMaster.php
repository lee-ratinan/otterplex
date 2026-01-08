<?php
return [
    'field' => [
        'id'                 => 'ไอดี',
        'branch_id'          => 'สาขา',
        'service_variant_id' => 'ตัวเลือกบริการ',
        'session_type'       => 'ประเภทเซสชั่น',
        'session_capacity'   => 'ความจุ',
        'short_description'  => 'คำอธิบาย',
        'date_start'         => 'วันที่เริ่ม',
        'date_end'           => 'วันที่สิ้นสุด',
    ],
    'enum'  => [
        'session_type' => [
            'OPEN'     => 'เซสชั่นแบบเปิดสำหรับให้ลงทะเบียน',
            'SPECIFIC' => 'เซสชั่นแบบปิดสำหรับการจองส่วนตัว'
        ]
    ]
];