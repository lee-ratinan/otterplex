<?php
return [
    'field' => [
        'id'                  => 'ไอดี',
        'product_id'          => 'สินค้า',
        'variant_slug'        => 'รหัสตัวเลือกสินค้า',
        'variant_sku'         => 'รหัส SKU',
        'variant_name'        => 'ชื่อตัวเลือกสินค้า',
        'variant_local_names' => 'ชื่อตัวเลือกในภาษาต่างๆ',
        'is_active'           => 'สถานะ',
        'inventory_count'     => 'จำนวนสินค้าในสต็อก',
        'price_active'        => 'ราคา',
        'price_compare'       => 'ราคาเปรียบเทียบ',
    ],
    'enum'  => [
        'is_active' => [
            'A' => 'ปกติ',
            'I' => 'สินค้าหมด',
        ],
    ]
];