<?php
return [
    'welcome-message' => 'ยินดีต้อนรับครับ, {0}!',
    'setup'           => [
        'title'                => 'การตั้งค่าระบบ',
        'health-check'         => 'ตรวจสอบสุขภาพระบบ',
        'variant_count'        => '{0} ตัวเลือก',
        'check-business-setup' => '<i class="fa-solid fa-circle-info text-warning"></i> ข้อมูลธุรกิจของคุณได้รับการตั้งค่าระหว่างการลงทะเบียน คุณสามารถตรวจสอบการตั้งค่าได้<a href="' . base_url('admin/business') . '">ที่นี่</a>.',
        'branches-you-have'    => '<i class="fa-solid fa-circle-check text-success"></i> ขณะนี้คุณมีสาขาทั้งหมด {0} สาขา',
        'branches-you-dont'    => '<i class="fa-solid fa-circle-xmark text-danger"></i> ขณะนี้คุณยังไม่ได้ตั้งค่าสาขาของคุณ กรุณาสร้างสาขาแรกของคุณ',
        'staff-you-have'       => '<i class="fa-solid fa-circle-check text-success"></i> ขณะนี้คุณได้ตั้งค่าพนักงานในสาขาต่างๆ ทั้งหมด {0} รายการ กรุณาตรวจสอบรายชื่อพนักงานในแต่ละสาขาเป็นระยะๆ เพื่อให้ข้อมูลเป็นปัจจุบันเสมอ',
        'staff-you-dont'       => '<i class="fa-solid fa-circle-xmark text-danger"></i> คุณยังไม่ได้ตั้งค่าพนักงานและบรรจุตำแหน่งพนักงานในสาขาต่างๆ กรุณาทำการตั้งค่าพนักงานรายแรกของคุณ',
        'services-you-have'    => '<i class="fa-solid fa-circle-check text-success"></i> ขณะนี้คุณมีบริการทั้งหมด {0} รายการ กรุณาตรวจสอบข้อมูลการให้บริการของคุณเป็นระยะๆ เพื่อให้ข้อมูลเป็นปัจจุบันเสมอ',
        'services-you-dont'    => '<i class="fa-solid fa-circle-xmark text-danger"></i> คุณยังไม่ได้ตั้งค่าบริการของคุณ กรุณาสร้างบริการรายการแรกของคุณ',
        'products-you-have'    => '<i class="fa-solid fa-circle-check text-success"></i> ขณะนี้คุณมีสินค้าทั้งหมด {0} รายการ กรุณาตรวจสอบรายการสินค้าของคุณเป็นระยะๆ เพื่อให้ข้อมูลเป็นปัจจุบันเสมอ',
        'products-you-dont'    => '<i class="fa-solid fa-circle-xmark text-danger"></i> คุณยังไม่ได้ตั้งค่าสินค้าของคุณ กรุณาสร้างสินค้ารายการแรกของคุณ',
    ]
];