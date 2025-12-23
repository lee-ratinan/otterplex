<?php
return [
    'field' => [
        'id'                    => 'ไอดี',
        'business_type_id'      => 'ประเภทธุรกิจ',
        'business_name'         => 'ชื่อธุรกิจ',
        'business_slug'         => 'รหัสธุรกิจ',
        'business_local_names'  => 'ชื่อธุรกิจในภาษาอื่น',
        'country_code'          => 'ประเทศ',
        'currency_code'         => 'สกุลเงิน',
        'tax_percentage'        => 'อัตราภาษี',
        'tax_inclusive'         => 'การคิดภาษี (VAT)',
        'mart_primary_color'    => 'สีหลักของมาร์เก็ตเพลส',
        'mart_text_color'       => 'สีข้อความของมาร์เก็ตเพลส',
        'mart_background_color' => 'สีพื้นหลังของมาร์เก็ตเพลส',
        'contract_expiry'       => 'วันหมดอายุสัญญา',
        'business_status'       => 'สถานะธุรกิจ', // Not a real field
    ],
    'enum'  => [
        'tax_inclusive'   => [
            'I' => 'รวมในราคา',
            'E' => 'ไม่รวมในราคา',
            'X' => 'ไม่ต้องคิดภาษี'
        ],
        'business_status' => [
            'A' => '<i class="fa-solid fa-circle-check text-success"></i> ปกติ',
            'E' => '<i class="fa-solid fa-circle-xmark text-danger"></i> หมดอายุ',
        ]
    ],
    'explanation' => [
        'business_name' => 'เมื่อคุณกดปุ่ม “อัปเดทรหัสธุรกิจ” ด้านล่าง ระบบจะทำการสร้างรหัสธุรกิจใหม่จากชื่อธุรกิจของคุณ รหัสธุรกิจนี้จะถูกใช้ในการสร้าง URL ไปยังหน้ามาร์เก็ตเพลสของคุณ<br><b class="text-danger"><i class="fa-solid fa-exclamation-triangle"></i> คำเตือน!</b> URL สำหรับมาร์เก็ตเพลสของคุณจะถูกเปลี่ยนทันทีเมื่อคุณบันทึกการเปลี่ยนแปลงหน้านี้ กรุณาปรับแก้ URL ตามเอกสารหรือหน้าเว็บต่างๆ ที่คุณลงไว้',
        'tax_inclusive' => 'ตั้งค่าวิธีคิดภาษี: <b>รวมในราคา</b> (มีภาษีอยู่ในราคาที่กำหนดแล้ว), <b>ไม่รวมในราคา</b> (บวกภาษีเพิ่มจากราคา), หรือ <b>ไม่ต้องคิดภาษี</b> (ธุรกิจไม่มีความจำเป็นต้องเสียภาษี)'
    ]
];