<?php
return [
    'field' => [
        'id'                   => '',
        'business_id'          => '',
        'product_category_id'  => '',
        'product_slug'         => '',
        'product_name'         => '',
        'product_local_names'  => '',
        'product_tag'          => '',
        'product_type'         => '',
        'is_active'            => '',
        'price_active_lowest'  => '',
        'price_compare_lowest' => ''
    ],
    'enum'  => [
        'product_tag'  => [
            'new' => '',
            'popular' => '',
            'recommended' => ''
        ],
        'product_type' => [
            'P' => 'Physical',
            'D' => 'Digital',
        ],
        'is_active'    => [
            'A' => 'Active',
            'I' => 'Inactive'
        ],
    ]
];