<?php
return [
    'field'       => [
        'id'                   => 'ID',
        'business_id'          => 'Business',
        'service_slug'         => 'Service Slug',
        'service_name'         => 'Service Name',
        'service_local_name'   => 'Service Name in Local Languages',
        'is_active'            => 'Status',
        'price_active_lowest'  => 'Starting Active Price',
        'price_compare_lowest' => 'Starting Full Price',
    ],
    'enum'        => [
        'is_active' => [
            'A' => 'Active',
            'I' => 'Inactive',
        ]
    ],
    'explanation' => [
        'price_active_lowest'  => 'Actual starting price that is charged.',
        'price_compare_lowest' => 'Full price that is shown to indicate that there is a discount.',
    ]
];