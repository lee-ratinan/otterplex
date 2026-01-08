<?php
return [
    'field' => [
        'id'                        => 'ID',
        'service_id'                => 'Service',
        'variant_slug'              => 'Variant Slug',
        'variant_name'              => 'Variant Name',
        'variant_local_names'       => 'Variant Local Names',
        'is_active'                 => 'Status',
        'schedule_type'             => 'Schedule Type (This field is not editable after you saved it.)',
        'variant_capacity'          => 'Variant Capacity',
        'price_active'              => 'Price',
        'price_compare'             => 'Compare Price',
        'required_num_staff'        => 'Staff Required (This field is not editable after you saved it.)',
        'required_resource_type_id' => 'Resource Type Required (This field is not editable after you saved it.)',
        'service_duration_minutes'  => 'Duration (min.) (This field is not editable after you saved it.)',
    ],
    'enum'  => [
        'is_active'     => [
            'A' => 'Active',
            'I' => 'Inactive',
        ],
        'schedule_type' => [
            'A' => 'Ad-hoc Session',
            'S' => 'Scheduled',
        ],
    ]
];