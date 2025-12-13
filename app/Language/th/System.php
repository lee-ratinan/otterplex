<?php
return [
    'site-name'       => 'ระบบ OtterNova',
    'copyrights'      => 'สงวนลิขสิทธิ์ &copy; 2025-{0} <b>OtterNova</b>',
    'change-language' => 'เปลี่ยนภาษา',
    'go-to-main-site' => 'ไปสู่หน้าหลัก',
    'pages'           => [
        'login'           => 'เข้าสู่ระบบ',
        'create-account'  => 'สร้างบัญชีใหม่',
        'forgot-password' => 'ลืมรหัสผ่าน',
        'reset-password'  => 'ตั้งรหัสผ่านใหม่',
    ],
    'response-msg'    => [
        'error'   => [
            'generic'                   => 'ขออภัย มีข้อผิดพลาดเกิดขึ้น กรุณาลองใหม่อีกครั้ง',
            'please-check-empty-field'  => 'กรุณาตรวจสอบข้อมูลที่จำเป็น',
            'password-does-not-matched' => 'ขออภัย รหัสผ่านใหม่ไม่ตรงกัน',
            'password-failed'           => 'ขออภัย เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน',
            'wrong-credentials'         => 'ชื่อผู้ใช้หรือรหัสผ่านผิด กรุณาลองใหม่อีกครั้ง',
            'inactive-account'          => 'บัญชีของคุณไม่สามารถเข้าสู่ระบบได้ กรุณาติดต่อแอดมินของคุณหรือส่งอีเมลหาทีมซัพพอร์ท',
            'not-logged-in'             => 'กรุณาเข้าสู่ระบบใหม่อีกครั้ง',
            'session-expired'           => 'ขออภัย การเข้าสู่ระบบของคุณนานเกินไป กรุณาเข้าสู่ระบบใหม่อีกครั้ง',
            'business-inactive'         => 'คุณไม่สามารถเปลี่ยนไปยังธุรกิจนี้ได้',
            'db-issue'                  => 'เกิดปัญหาระหว่างการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง',
            'upload-failed'             => 'ขออภัย เกิดข้อผิดพลาดในการอัปโหลดไฟล์',
            'removed'                   => 'ขออภัย เกิดข้อผิดพลาดในการลบไฟล์',
            'no-permission'             => 'คุณไม่มีสิทธิเพียงพอในการเข้าใช้ฟีเจอร์นี้',
            'account-created-issue'     => 'ขออภัย เกิดข้อผิดพลาดขณะสร้างบัญชีของคุณ',
        ],
        'success' => [
            'business-switched'     => 'คุณได้ทำการเปลี่ยนธุรกิจเรียบร้อยแล้ว',
            'data-saved'            => 'ข้อมูลของคุณได้รับการบันทึกเรียบร้อยแล้ว',
            'password-changed'      => 'รหัสผ่านของคุณได้รับการแก้ไขแล้ว',
            'uploaded'              => 'อัปโหลดเสร็จสิ้น',
            'removed'               => 'ลบไฟล์เสร็จสิ้น',
            'contract-renewal-done' => 'การต่ออายุได้รับการบันทึกเรียบร้อยแล้ว กรุณาชำระค่าบริการ',
            'account-created'       => 'บัญชีของคุณได้รับการสร้างเรียบร้อยแล้ว กรุณาตรวจสอบอีเมลของคุณเพื่อยืนยันบัญชีก่อนเข้าสู่ระบบ',
        ]
    ],
    'buttons'         => [
        'new'             => '<i class="bi bi-plus-circle"></i> สร้างใหม่',
        'edit'            => '<i class="bi bi-pencil"></i> แก้ไข',
        'save'            => '<i class="bi bi-floppy"></i> บันทึก',
        'upload'          => '<i class="bi bi-cloud-upload"></i> อัปโหลด',
        'remove'          => '<i class="bi bi-trash"></i> ลบ',
        'remove-confirm'  => '<i class="bi bi-exclamation-triangle"></i> ยืนยันการลบ',
        'switch-role'     => 'เปลี่ยนตำแหน่ง',
        'switch-business' => 'เปลี่ยนธุรกิจ',
        'filter'          => 'กรอง',
        'reset'           => 'รีเซ็ต',
        'view-more'       => 'ดูเพิ่มเติม',
    ],
    'generic-term'    => [
        'no-data' => 'ไม่พบข้อมูล'
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
            'confirm-password'      => 'ยืนยันรหัสผ่าน',
            'business-name'         => 'ชื่อธุรกิจของคุณ',
            'country-code'          => 'ประเทศ',
            'country-code-note'     => 'คุณไม่สามารถแก้ไขข้อมูลประเทศได้หลังจากบัญชีของคุณสร้างขึ้นแล้ว',
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
        'instruction'     => 'กรุณากรอกรหัสผ่านใหม่',
        'fields'          => [
            'password'                     => 'รหัสผ่านใหม่',
            'password-confirm'             => 'ยืนยันรหัสผ่าน',
            'password-empty-error'         => 'กรุณากรอกรหัสผ่านใหม่',
            'password-confirm-empty-error' => 'กรุณายืนยันรหัสผ่าน',
        ],
        'change-password' => 'เปลี่ยนรหัสผ่าน',
        'token-invalid'   => 'รหัสคำขอเปลี่ยนรหัสผ่านใหม่ไม่ถูกต้อง หรือหมดอายุ กรุณาเริ่มต้นใหม่อีกครั้ง'
    ],
    'email'           => [
        'footer-line'    => 'อีเมลฉบับนี้ถูกสร้างขึ้นโดยระบบ',
        'footer-privacy' => 'นโยบายความเป็นส่วนตัว',
        'footer-terms'   => 'เงื่อนไขการใช้บริการ',
    ]
];
