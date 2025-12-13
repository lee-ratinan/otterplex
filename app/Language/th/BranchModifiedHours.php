<?php
return [
    'field' => [
        'id'                    => 'ไอดี',
        'branch_id'             => 'สาขา',
        'modified_hours_date'   => 'วันที่',
        'modified_reason'       => 'เหตุผล',
        'modified_type'         => 'ประเภท',
        'updated_opening_hours' => 'เวลาเปิด',
        'updated_closing_hours' => 'เวลาปิด',
    ],
    'enum'  => [
        'modified_type' => [
            'MODIFIED_HOURS' => 'เปลี่ยนเวลาเปิดร้าน',
            'CLOSED'         => 'ปิดร้าน'
        ]
    ]
];