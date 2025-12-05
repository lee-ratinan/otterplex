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
        'user_name_first'    => 'ชื่จริง',
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
            'A' => 'ปกติ',
            'P' => 'รอยืนยัน',
            'B' => 'บล็อก',
            'S' => 'ยกเลิก'
        ],
        'user_gender'    => [
            'M'  => 'ชาย',
            'F'  => 'หญิง',
            'NB' => 'นอนไบนารี',
            'U'  => 'ไม่ระบุ'
        ]
    ]
];