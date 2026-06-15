<?php
return [
    'field'       => [
        'id'                        => 'ID',
        'service_id'                => 'Service',
        'variant_slug'              => 'Variant Slug',
        'variant_name'              => 'Variant Name',
        'variant_local_names'       => 'Variant Local Names',
        'variant_description'       => 'Variant Description',
        'is_active'                 => 'Status',
        'schedule_type'             => 'Schedule Type',
        'variant_capacity'          => 'Variant Capacity',
        'price_active'              => 'Price',
        'price_compare'             => 'Compare Price',
        'required_num_staff'        => 'Staff Required',
        'required_resource_type_id' => 'Resource Type Required',
        'service_duration_minutes'  => 'Duration',
    ],
    'enum'        => [
        'is_active'     => [
            'A' => 'Active',
            'I' => 'Inactive',
        ],
        'schedule_type' => [
            'A' => 'Ad-hoc Session',
            'S' => 'Scheduled',
        ],
    ],
    'explanation' => [
        'required_num_staff'        => 'The number of staff required to be booked for this service (0-1 person).',
        'required_resource_type_id' => 'The resource that is required for this service (if any).',
        'price_compare'             => 'The higher price that is used to compare to the actual price.',
        'service_duration_minutes'  => 'The total length of this service (in minutes)',
        'not_editable'              => 'This field is not editable after you saved it.'
    ]
];