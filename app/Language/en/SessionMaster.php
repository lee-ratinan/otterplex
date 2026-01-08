<?php
return [
    'field' => [
        'id'                 => 'ID',
        'branch_id'          => 'Branch',
        'service_variant_id' => 'Service Variant',
        'session_type'       => 'Session Type',
        'session_capacity'   => 'Session Capacity',
        'short_description'  => 'Short Description',
        'date_start'         => 'Start Date',
        'date_end'           => 'End Date',
    ],
    'enum'  => [
        'session_type' => [
            'OPEN'     => 'Open session for anyone to register',
            'SPECIFIC' => 'Session for specific booking'
        ]
    ]
];