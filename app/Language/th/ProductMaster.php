<?php
return [
    'field' => [
        'id'                   => 'ไอดี',
        'business_id'          => 'ธุรกิจ',
        'product_category_id'  => 'ประเภทสินค้า',
        'product_slug'         => 'รหัสสินค้า',
        'product_name'         => 'ชื่อสินค้า',
        'product_local_names'  => 'ชื่อสินค้าในภาษาต่างๆ',
        'product_tag'          => 'แท็กสินค้า',
        'product_type'         => 'ลักษณะสินค้า',
        'is_active'            => 'สถานะ',
        'price_active_lowest'  => 'ราคาขาย',
        'price_compare_lowest' => 'ราคาเปรียบเทียบ'
    ],
    'enum'  => [
        'product_tag'  => [
            'new'         => 'ใหม่',
            'popular'     => 'ยอดฮิต',
            'recommended' => 'แนะนำ'
        ],
        'product_type' => [
            'P' => 'สิ่งของ',
            'D' => 'ดิจิทัล',
        ],
        'is_active'    => [
            'A' => 'ปกติ',
            'I' => 'ไม่มีสินค้า'
        ],
    ]
];