<?php
return [
    'field' => [
        'id'                  => 'ไอดี',
        'business_id'         => 'ธุรกิจ',
        'user_id'             => 'ผู้ใช้',
        'user_role'           => 'ตำแหน่งของพนักงาน',
        'role_status'         => 'สถานะของตำแหน่ง',
        'my_default_business' => 'ธุรกิจเริ่มต้น',
    ],
    'enum'  => [
        'user_role'           => [
            'OWNER'   => 'เจ้าของ',
            'MANAGER' => 'ผู้จัดการ',
            'STAFF'   => 'พนักงาน'
        ],
        'role_status'         => [
            'REQUESTED' => 'ร้องขอ', // cancel this status
            'ACTIVE'    => '<i class="fa-solid fa-circle-check text-success"></i> ปกติ',
            'REVOKED'   => '<i class="fa-solid fa-circle-xmark text-danger"></i> เพิกถอน',
        ],
        'my_default_business' => [
            'Y' => 'ใช่',
            'N' => 'ไม่ใช่',
        ]
    ]
];