<?php
return [
    'field'       => [
        'id'                   => 'ไอดี',
        'business_id'          => 'ธุรกิจ',
        'service_slug'         => 'รหัสบริการ',
        'service_name'         => 'ชื่อบริการ',
        'service_local_names'  => 'ชื่อบริการในภาษาต่างๆ',
        'is_active'            => 'สถานะ',
        'price_active_lowest'  => 'ราคาจริงเริ่มต้น',
        'price_compare_lowest' => 'ราคาเต็มเริ่มต้น',
        'service_description'  => 'คำอธิบายสินค้า'
    ],
    'enum'        => [
        'is_active' => [
            'A' => 'ปกติ',
            'I' => 'ไม่ให้บริการนี้',
        ]
    ],
    'explanation' => [
        'price_active_lowest'  => 'ราคาเริ่มต้นที่ใช้จริง',
        'price_compare_lowest' => 'ราคาเต็มสำหับการเปรียบเทียบให้ลูกค้าเห็นว่ามีการลดราคา',
    ]
];