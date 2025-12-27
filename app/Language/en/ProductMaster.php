<?php
return [
    'field' => [
        'id'                   => 'ID',
        'business_id'          => 'Business',
        'product_category_id'  => 'Category',
        'product_slug'         => 'Product Slug',
        'product_name'         => 'Product Name',
        'product_local_names'  => 'Product Names in Local Languages',
        'product_tag'          => 'Product Tag',
        'product_type'         => 'Product Type',
        'is_active'            => 'Status',
        'price_active_lowest'  => 'Active Price',
        'price_compare_lowest' => 'Compare Price',
        'product_description'  => 'Product Description',
    ],
    'enum'  => [
        'product_tag'  => [
            'new'         => 'New',
            'popular'     => 'Popular',
            'recommended' => 'Recommended',
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