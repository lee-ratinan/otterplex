<?php
return [
    'field' => [
        'id'                    => 'ID',
        'business_type_id'      => 'Business Type',
        'business_name'         => 'Business Name',
        'business_slug'         => 'Business Slug',
        'business_local_names'  => 'Business Local Names',
        'country_code'          => 'Country',
        'currency_code'         => 'Currency',
        'tax_percentage'        => 'Tax Percentage',
        'tax_inclusive'         => 'Tax Inclusive',
        'mart_primary_color'    => 'Martketplace’s Primary Color',
        'mart_text_color'       => 'Martketplace’s Text Color',
        'mart_background_color' => 'Martketplace’s Background Color',
        'contract_expiry'       => 'Contract Expiry Date',
        'business_status'       => 'Business Status', // Not a real field
    ],
    'enum'  => [
        'tax_inclusive'   => [
            'I' => 'Inclusive',
            'E' => 'Exclusive',
        ],
        'business_status' => [
            'A' => '<i class="bi bi-check-circle-fill text-success"></i> Active',
            'E' => '<i class="bi bi-x-circle-fill text-danger"></i> Expired',
        ]
    ]
];