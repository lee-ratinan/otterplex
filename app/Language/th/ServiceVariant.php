<?php
return [
    'field'       => [
        'id'                        => 'ไอดี',
        'service_id'                => 'บริการ',
        'variant_slug'              => 'รหัสตัวเลือกบริการ',
        'variant_name'              => 'ชื่อตัวเลือก',
        'variant_local_names'       => 'ชื่อตัวเลือกในภาษาต่างๆ',
        'variant_description'       => 'คำอธิบายตัวเลือก',
        'is_active'                 => 'สถานะ',
        'schedule_type'             => 'ประเภทการนัดหมาย',
        'variant_capacity'          => 'จำนวนลูกค้าที่รับได้',
        'price_active'              => 'ราคา',
        'price_compare'             => 'ราคาเปรียบเทียบ',
        'required_num_staff'        => 'จำนวนพนักงานที่ใช้',
        'required_resource_type_id' => 'ประเภทของทรัพยากรที่ต้องใช้',
        'service_duration_minutes'  => 'ระยะเวลา',
    ],
    'enum'        => [
        'is_active'     => [
            'A' => 'ปกติ',
            'I' => 'สินค้าหมด',
        ],
        'schedule_type' => [
            'A' => 'เลือกเวลาเอง',
            'S' => 'มีกำหนดตารางเวลา',
        ],
    ],
    'explanation' => [
        'required_num_staff'        => 'จำนวนพนักงานที่จำเป็นต้องใช้สำหรับบริการนี้ (0-1 คน)',
        'required_resource_type_id' => 'ทรัพยากรที่จำเป็นต้องใช้ (ถ้ามี)',
        'price_compare'             => 'ราคาที่สูงกว่าที่ใช้สำหรับเปรียบเทียบ',
        'service_duration_minutes'  => 'ระยะเวลาทั้งหมดที่ใช้ในการให้บริการนี้ (นาที)',
        'not_editable'              => 'ข้อมูลนี้หลังจากตั้งค่าแล้วจะแก้ไขไม่ได้'
    ]
];