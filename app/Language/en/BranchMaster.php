<?php
return [
    'field' => [
        'id'                 => 'ID',
        'business_id'        => 'Business',
        'subdivision_code'   => 'Location',
        'branch_name'        => 'Branch Name',
        'branch_slug'        => 'Branch Slug',
        'branch_local_names' => 'Branch Local Names',
        'timezone_code'      => 'Timezone',
        'branch_type'        => 'Branch Type',
        'branch_address'     => 'Address',
        'branch_postal_code' => 'Postal Code',
        'branch_status'      => 'Status',
    ],
    'enum'  => [
        'branch_type'   => [
            'PHYSICAL' => 'Physical',
            'ONLINE'   => 'Online',
        ],
        'branch_status' => [
            'ACTIVE'   => 'Active',
            'INACTIVE' => 'Inactive',
        ],
    ]
];