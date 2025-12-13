<?php
return [
    'field' => [
        'id'                 => 'ไอดี',
        'business_id'        => 'ธุรกิจ',
        'subdivision_code'   => 'ที่ตั้ง',
        'branch_name'        => 'ชื่อสาขา',
        'branch_slug'        => 'รหัสสาขา',
        'branch_local_names' => 'ชื่อสาขาในภาษาอื่น',
        'timezone_code'      => 'โซนเวลา',
        'branch_type'        => 'ประเภทสาขา',
        'branch_address'     => 'ที่อยู่',
        'branch_postal_code' => 'รหัสไปรษณีย์',
        'branch_status'      => 'สถานะ',
    ],
    'enum'  => [
        'branch_type'   => [
            'PHYSICAL' => 'สาขา (มีหน้าร้าน)',
            'ONLINE'   => 'ออนไลน์',
        ],
        'branch_status' => [
            'ACTIVE'   => 'ปกติ',
            'INACTIVE' => 'ปิด',
        ],
    ]
];