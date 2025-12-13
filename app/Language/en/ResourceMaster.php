<?php
return [
    'field' => [
        'id'                   => 'ID',
        'branch_id'            => 'Branch',
        'resource_type_id'     => 'Resource Type',
        'resource_name'        => 'Resource Name',
        'resource_description' => 'Description',
        'is_active'            => 'Status',
    ],
    'enum'  => [
        'is_active' => [
            'A' => 'Active',
            'I' => 'Inactive',
            'D' => 'Deleted',
        ]
    ]
];