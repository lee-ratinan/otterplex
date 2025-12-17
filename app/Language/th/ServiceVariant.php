<?php
return [
    'field' => [
        'id'                        => 'ไอดี',
        'service_id'                => 'บริการ',
        'variant_slug'              => 'รหัสตัวเลือกบริการ',
        'variant_name'              => 'ชื่อตัวเลือก',
        'variant_local_names'       => 'ชื่อตัวเลือกในภาษาต่างๆ',
        'is_active'                 => 'สถานะ',
        'schedule_type'             => 'ประเภทการนัดหมาย',
        'variant_capacity'          => 'จำนวนลูกค้าที่รับได',
        'price_active'              => 'ราคา',
        'price_compare'             => 'ราคาเปรียบเทียบ',
        'required_num_staff'        => 'จำนวนพนักงานที่ใช้',
        'required_resource_type_id' => 'ประเภทของทรัพยากรที่ต้องใช้',
        'service_duration_minutes'  => 'ระยะเวลา (นาที)',
    ],
    'enum'  => [
        'is_active'     => [
            'A' => 'Active',
            'B' => 'Inactive',
        ],
        'schedule_type' => [
            'A' => 'เลือกเวลาเอง',
            'S' => 'มีกำหนดตารางเวลา',
        ],
    ]
];