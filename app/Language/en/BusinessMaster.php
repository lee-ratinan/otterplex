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
            'X' => 'Not Applicable'
        ],
        'business_status' => [
            'A' => '<i class="fa-solid fa-circle-check text-success"></i> Active',
            'E' => '<i class="fa-solid fa-circle-xmark text-danger"></i> Expired',
        ]
    ],
    'explanation' => [
        'business_slug' => 'The slug is used to create your marketplace URL. It’s auto-generated, but you can override it using only <b>a–z</b>, <b>0–9</b>, and <b>dashes</b>; all other characters will be removed automatically.',
        'tax_inclusive' => 'Choose how tax is handled: <b>Inclusive</b> (tax is already inside the price), <b>Exclusive</b> (tax is added on top), or <b>Not Applicable</b> (your business is not required to charge tax).'
    ]
];