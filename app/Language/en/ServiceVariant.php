<?php
return [
    'field' => [
        'id'                       => 'ID',
        'service_id'               => 'Service',
        'variant_slug'             => 'Variant Slug',
        'variant_name'             => 'Variant Name',
        'variant_local_names'      => 'Variant Local Names',
        'is_active'                => 'Status',
        'schedule_type'            => 'Schedule Type',
        'variant_capacity'         => 'Variant Capacity',
        'price_active'             => 'Price',
        'price_compare'            => 'Compare Price',
        'required_num_staff'       => 'Staff Required',
        'required_resource_type'   => 'Resource Type Required',
        'service_duration_minutes' => 'Service Duration in Minutes',
    ],
    'enum'  => [
        'is_active'     => [
            'A' => 'Active',
            'B' => 'Inactive',
        ],
        'schedule_type' => [
            'A' => 'Ad-hoc Session',
            'S' => 'Scheduled',
        ],
    ]
];