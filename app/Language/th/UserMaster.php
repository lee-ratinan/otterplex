<?php
return [
    'field' => [
        'id'                 => 'ไอดี',
        'email_address'      => 'อีเมล',
        'password_hash'      => 'รหัสผ่าน',
        'current_password'   => 'รหัสผ่านปัจจุบัน',
        'new_password'       => 'รหัสผ่านใหม่',
        'confirm_password'   => 'ยืนยันรหัสผ่าน',
        'password_expiry'    => 'วันหมดอายุรหัสผ่าน',
        'telephone_number'   => 'หมายเลขโทรศัพท์',
        'lang_code'          => 'ภาษา',
        'account_status'     => 'สถานะบัญชี',
        'user_name_first'    => 'ชื่อจริง',
        'user_name_last'     => 'นามสกุล',
        'user_full_name'     => 'ชื่อเต็ม',
        'user_gender'        => 'เพศ',
        'user_date_of_birth' => 'วันเกิด',
        'user_nationality'   => 'สัญชาติ',
        'profile_status_msg' => 'สถานะโปรไฟล์',
        'user_type'          => 'ประเภทผู้ใช้',
    ],
    'enum'  => [
        'account_status' => [
            'A' => '<i class="fa-solid fa-circle-check text-success"></i> ปกติ',
            'P' => '<i class="fa-solid fa-circle-question tetx-warning"></i> รอยืนยัน',
            'B' => '<i class="fa-solid fa-circle-xmark text-danger"></i> บล็อก',
            'S' => '<i class="fa-solid fa-circle-xmark text-danger"></i> ยกเลิก'
        ],
        'user_gender'    => [
            'M'  => 'ชาย',
            'F'  => 'หญิง',
            'NB' => 'นอนไบนารี',
            'U'  => 'ไม่ระบุ'
        ]
    ]
];