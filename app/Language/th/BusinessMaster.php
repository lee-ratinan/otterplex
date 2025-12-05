<?php
return [
    'field' => [
        'id'                   => 'ไอดี',
        'business_type_id'     => 'ประเภทธุรกิจ',
        'business_name'        => 'ชื่อธุรกิจ',
        'business_slug'        => 'รหัสธุรกิจ',
        'business_local_names' => 'ชื่อธุรกิจในภาษาอื่น',
        'country_code'         => 'ประเทศ',
        'currency_code'        => 'สกุลเงิน',
        'tax_percentage'       => 'อัตราภาษี',
        'tax_inclusive'        => 'การคิดภาษี',
        'contract_expiry'      => 'วันหมดอายุสัญญา',
        'business_status'      => 'สถานะธุรกิจ', // Not a real field
    ],
    'enum'  => [
        'tax_inclusive' => [
            'I' => 'รวมในราคา',
            'E' => 'ไม่รวมในราคา',
        ],
        'business_status' => [
            'A' => '<i class="bi bi-check-circle-fill text-success"></i> ปกติ',
            'E' => '<i class="bi bi-x-circle-fill text-danger"></i> หมดอายุ',
        ]
    ]
];