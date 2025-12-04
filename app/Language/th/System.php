<?php
return [
    'site-name'       => 'ระบบ OtterNova',
    'change-language' => 'เปลี่ยนภาษา',
    'go-to-main-site' => 'ไปสู่หน้าหลัก',
    'pages'           => [
        'login'           => 'เข้าสู่ระบบ',
        'create-account'  => 'สร้างบัญชีใหม่',
        'forgot-password' => 'ลืมรหัสผ่าน',
        'reset-password'  => 'ตั้งรหัสผ่านใหม่',
    ],
    'login'           => [
        'title'             => 'เข้าสู่ระบบ',
        'instruction'       => 'กรุณาเข้าสู่ระบบด้วยบัญชีอีเมล',
        'fields'            => [
            'username'             => 'ชื่อผู้ใช้ (บัญชีอีเมล)',
            'username-empty-error' => 'กรุณากรอกชื่อผู้ใช้ (บัญชีอีเมล)',
            'password'             => 'รหัสผ่าน',
            'password-empty-error' => 'กรุณากรอกรหัสผ่าน.',
        ],
        'dont-have-account' => 'ยังไม่มีบัญชี?',
    ],
    'create-account'  => [
        'instruction'          => 'Enter your personal details to create account',
        'fields'               => [
            'full-name'             => 'ชื่อ-สกุล',
            'full-name-empty-error' => 'กรุณากรอกชื่อ-สกุล',
            'first-name'            => 'ชื่อจริง',
            'last-name'             => 'นามสกุล',
            'username'              => 'ชื่อผู้ใช้ (บัญชีอีเมล)',
            'username-empty-error'  => 'กรุณากรอกชื่อผู้ใช้ (บัญชีอีเมล)',
            'password'              => 'รหัสผ่าน',
            'password-empty-error'  => 'กรุณากรอกรหัสผ่าน',
            'plan'                  => 'แพลนที่ต้องการ',
            'plan-empty-error'      => 'กรุณาเลือกแพลนของคุณ',
            'plan-options'          => [
                'basic'    => 'เบสิก',
                'standard' => 'สแตนดาร์ด',
                'premium'  => 'พรีเมียม',
            ],
            'i-agree'               => 'อ่านและตกลงใน',
            'terms'                 => 'เงื่อนไขการใช้งาน',
            'terms-error'           => 'คุณต้องตกลงกับเงื่อนไขการใช้งานก่อนสร้างบัญชีของคุณ'
        ],
        'already-have-account' => 'มีบัญชีอยู่แล้ว?',
    ],
    'forgot-password' => [
        'instruction'    => 'กรุณากรอกบัญชีอีเมลของคุณและทำตามขั้นตอนเพื่อตั้งรหัสผ่านใหม่',
        'email-address'  => 'บัญชีอีเมล',
        'submit'         => 'ตั้งค่ารหัสผ่านใหม่',
        'submit-success' => 'คำขอตั้งค่ารหัสผ่านใหม่ได้รับเป็นที่เรียบร้อยแล้ว กรุณาเช็คอีเมลของคุณและคลิกลิงก์เพื่อยืนยันการเริ่มต้นตั้งรหัสผ่านใหม่ภายใน 5 นาที',
        'submit-error'   => 'มีปัญหาบางอย่างเกิดขึ้น กรุณาลองใหม่อีกครั้ง'
    ],
    'reset-password'  => [
        'instruction' => 'กรุณากรอกรหัสผ่านใหม่',
        'fields'      => [
            'password'                     => 'รหัสผ่านใหม่',
            'password-confirm'             => 'ยืนยันรหัสผ่าน',
            'password-empty-error'         => 'กรุณากรอกรหัสผ่านใหม่',
            'password-confirm-empty-error' => 'กรุณายืนยันรหัสผ่าน',
        ],
        'change-password' => 'เปลี่ยนรหัสผ่าน',
        'token-invalid'   => 'รหัสคำขอเปลี่ยนรหัสผ่านใหม่ไม่ถูกต้อง หรือหมดอายุ กรุณาเริ่มต้นใหม่อีกครั้ง'
    ]
];
