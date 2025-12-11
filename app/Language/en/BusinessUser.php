<?php
return [
    'field' => [
        'id'                  => 'ID',
        'business_id'         => 'Business',
        'user_id'             => 'User',
        'user_role'           => 'User Role',
        'role_status'         => 'Role Status',
        'my_default_business' => 'Default Business',
    ],
    'enum'  => [
        'user_role'           => [
            'OWNER'   => 'Owner',
            'MANAGER' => 'Manager',
            'STAFF'   => 'Staff'
        ],
        'role_status'         => [
            'REQUESTED' => 'Requested', // cancel this status
            'ACTIVE'    => '<i class="bi bi-check-circle-fill text-success"></i> Active',
            'REVOKED'   => '<i class="bi bi-x-circle-fill text-danger"></i> Revoked',
        ],
        'my_default_business' => [
            'Y' => 'Yes',
            'N' => 'No',
        ]
    ]
];