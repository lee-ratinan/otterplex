<?php
return [
    'field' => [
        'id'                   => 'ไอดี',
        'branch_id'            => 'สาขา',
        'resource_type_id'     => 'ประเภททรัพยากร',
        'resource_name'        => 'ชื่อทรัพยากร',
        'resource_description' => 'คำอธิบาย',
        'is_active'            => 'สถานะ',
    ],
    'enum'  => [
        'is_active' => [
            'A' => 'ปกติ',
            'I' => 'ไม่ได้ใช้งาน',
            'D' => 'ลบ',
        ]
    ]
];