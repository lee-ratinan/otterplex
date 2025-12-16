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
            'ACTIVE'    => '<i class="fa-solid fa-circle-check text-success"></i> Active',
            'REVOKED'   => '<i class="fa-solid fa-circle-xmark text-danger"></i> Revoked',
        ],
        'my_default_business' => [
            'Y' => 'Yes',
            'N' => 'No',
        ]
    ]
];