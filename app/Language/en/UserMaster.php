<?php
return [
    'field' => [
        'id'                 => 'ID',
        'email_address'      => 'Email Address',
        'password_hash'      => 'Password',
        'current_password'   => 'Current Password',
        'new_password'       => 'New Password',
        'confirm_password'   => 'Confirm Password',
        'password_expiry'    => 'Password Expiry Date',
        'telephone_number'   => 'Telephone Number',
        'lang_code'          => 'Language',
        'account_status'     => 'Account Status',
        'user_name_first'    => 'First Name',
        'user_name_last'     => 'Last Name',
        'user_full_name'     => 'Full Name',
        'user_gender'        => 'Gender',
        'user_date_of_birth' => 'Date Of Birth',
        'user_nationality'   => 'Nationality',
        'profile_status_msg' => 'Profile Status',
        'user_type'          => 'User Type',
    ],
    'enum'  => [
        'account_status' => [
            'A' => '<i class="fa-solid fa-circle-check text-success"></i> Active',
            'P' => '<i class="fa-solid fa-circle-question tetx-warning"></i> Pending',
            'B' => '<i class="fa-solid fa-circle-xmark text-danger"></i> Blocked',
            'S' => '<i class="fa-solid fa-circle-xmark text-danger"></i> Suspended'
        ],
        'user_gender'    => [
            'M'  => 'Male',
            'F'  => 'Female',
            'NB' => 'Non-Binary',
            'U'  => 'Unknown'
        ]
    ]
];