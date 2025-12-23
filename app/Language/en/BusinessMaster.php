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
        'business_name' => 'By clicking “Update Slug” button below, the system will generate slug based on your business name. The slug is used to generate a link to your marketplace page.<br><b class="text-danger"><i class="fa-solid fa-exclamation-triangle"></i> CAUTION!</b> Your marketplace URL will be changed when you save this business. Please ensure you have updated the URL accordingly.',
        'tax_inclusive' => 'Choose how tax is handled: <b>Inclusive</b> (tax is already inside the price), <b>Exclusive</b> (tax is added on top), or <b>Not Applicable</b> (your business is not required to charge tax).'
    ]
];