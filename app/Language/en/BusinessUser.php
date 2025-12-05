<?php
return [
    'field' => [
        'id'          => 'ID',
        'business_id' => 'Business',
        'user_id'     => 'User',
        'user_role'   => 'Role',
        'role_status' => 'Status',
    ],
    'enum'  => [
        'user_role'           => [
            'OWNER'   => 'Owner',
            'MANAGER' => 'Manager',
            'STAFF'   => 'Staff'
        ],
        'role_status'         => [
            'REQUESTED' => 'Requested',
            'ACTIVE'    => 'Active',
            'REVOKED'   => 'Revoked',
        ],
        'my_default_business' => [
            'Y' => 'Yes',
            'N' => 'No',
        ]
    ]
];