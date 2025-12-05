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
            'A' => 'Active',
            'P' => 'Pending',
            'B' => 'Blocked',
            'S' => 'Suspended'
        ],
        'user_gender'    => [
            'M'  => 'Male',
            'F'  => 'Female',
            'NB' => 'Non-Binary',
            'U'  => 'Unknown'
        ]
    ]
];