<?php
return [
    'field' => [
        'business_id'          => 'ธุรกิจ',
        'customer_id'          => 'ลูกค้า',
        'customer_address_id'  => 'ที่อยู่ลูกค้า',
        'order_number'         => 'หมายเลขออร์เดอร์',
        'order_subtotal'       => 'ยอดรวม',
        'order_adjustment'     => 'ยอดรวมภาษี/ค่าขนส่ง/ส่วนลด',
        'order_total'          => 'จำนวนเงินสุทธิ',
        'shipping_option'      => 'ตัวเลือกการขนส่ง',
        'payment_method'       => 'วิธีการชำระเงิน',
        'collection_branch_id' => 'สาขาที่รับสินค้า',
        'order_status'         => 'สถานะออร์เดอร์',
        'financial_status'     => 'สถานะการเงิน',
        'shipping_status'      => 'สถานะขนส่ง',
        'staff_comment'        => 'คอมเมนต์พนักงาน',
        'customer_comment'     => 'คอมเมนต์ลูกค้า',
    ],
    'enum'  => [
        'shipping_option'  => [
            'NOT_APPLICABLE'  => 'ไม่ต้องส่งสินค้า',
            'SELF_COLLECTION' => 'รับสินค้าด้วยตนเอง',
            'SHIPPING'        => 'ส่งสินค้า'
        ],
        'order_status'     => [
            'OPEN'     => 'เปิด',
            'CLOSED'   => 'ปิด',
            'CANCELED' => 'ยกเลิก'
        ],
        'financial_status' => [
            'PENDING'            => 'ยังไม่จ่าย',
            'PAID'               => 'จ่ายแล้ว',
            'PARTIALLY_PAID'     => 'จ่ายบางส่วน',
            'REFUNDED'           => 'คืนเงินแล้ว',
            'PARTIALLY_REFUNDED' => 'คืนเงินบางส่วน'
        ],
        'shipping_status'  => [
            'OPEN'           => 'เปิด',
            'IN_PROGRESS'    => 'กำลังดำเนินการ',
            'SHIPPED'        => 'ส่งแล้ว',
            'RETURNED'       => 'คืนแล้ว',
            'NOT_APPLICABLE' => 'ไม่ต้องส่งสินค้า'
        ],
    ]
];