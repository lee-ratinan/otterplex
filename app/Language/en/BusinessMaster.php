<?php
return [
    'field'       => [
        'id'                         => 'ID',
        'business_type_id'           => 'Business Type',
        'business_name'              => 'Business Name',
        'business_slug'              => 'Business Slug',
        'business_local_names'       => 'Business Local Names',
        'country_code'               => 'Country',
        'currency_code'              => 'Currency',
        'tax_percentage'             => 'Tax Percentage',
        'tax_inclusive'              => 'Tax Inclusive',
        'mart_primary_color'         => 'Martketplace’s Primary Color',
        'mart_text_color'            => 'Martketplace’s Text Color',
        'mart_background_color'      => 'Martketplace’s Background Color',
        'mart_meta_description'      => 'Meta Description (SEO Field)',
        'mart_meta_keywords'         => 'Search Keywords (SEO Field)',
        'mart_store_intro_paragraph' => 'Business’s Description (on the website)',
        'social_media'               => 'Social Media',
        'contract_plan'              => 'Plan',
        'contract_expiry'            => 'Contract Expiry Date',
        'business_status'            => 'Business Status', // Not a real field
        'allow_advance_booking'      => 'Days allowed for advance booking (for services with advance booking)',
        'contact'                    => 'Contact Information',
        'contact_email_address'      => 'Email Address',
        'contact_phone_number'       => 'Phone Number',
        'contact_website'            => 'Website',
        'live_status'                => 'Store’s Online Status',
        'shipping'                   => 'Shipping (for physical products, if any)',
        'shipping_options'           => 'Shipping Options',
        'shipping_fee_taxable'       => 'Tax Shipping Fee',
        'review_stars'               => 'Review',
    ],
    'enum'        => [
        'tax_inclusive'        => [
            'I' => 'Inclusive',
            'E' => 'Exclusive',
            'X' => 'Not Applicable'
        ],
        'business_status'      => [
            'A' => '<i class="fa-solid fa-circle-check text-success"></i> Active',
            'E' => '<i class="fa-solid fa-circle-xmark text-danger"></i> Expired',
        ],
        'live_status'          => [
            'Y' => 'Online',
            'N' => 'Offline'
        ],
        'shipping_options'     => [
            'SHIPPING'        => 'Shipping',
            'SELF-COLLECTION' => 'Self collection',
            'BOTH'            => 'Both'
        ],
        'shipping_fee_taxable' => [
            'Y' => 'Taxable',
            'N' => 'Not Taxable'
        ],
        'contract_plan'        => [
            'free'     => 'Free',
            'basic'    => 'Basic',
            'standard' => 'Standard',
            'premium'  => 'Premium',
        ]
    ],
    'explanation' => [
        'business_name'         => 'By clicking the “Update Slug” button below, the system will generate a new slug based on your business name. The slug is used to generate a link to your marketplace page.<br><b class="text-danger"><i class="fa-solid fa-exclamation-triangle"></i> CAUTION!</b> Your marketplace URL will be changed when you save this business. Please ensure you have updated the URL accordingly.',
        'tax_inclusive'         => 'Choose how tax is handled:<br />- <b>Inclusive</b> (tax is already inside the price),<br />- <b>Exclusive</b> (tax is added on top),<br />- <b>Not Applicable</b> (your business is not required to charge tax).',
        'currency_code'         => 'The currency code cannot be changed when the business is <b>live</b>.<br /><b class="text-danger"><i class="fa-solid fa-exclamation-triangle"></i> CAUTION!</b> Please note that all prices will not be changed, accordingly. So, if you changed the currency from THB to USD, any item that costs 100 baht will be 100 US dollars immediately.',
        'mart_meta_description' => 'This is a short description, around 20-40 words, describing your business.',
        'mart_meta_keywords'    => 'This is a list of keywords that explains your business that your customers could use to find your business.',
        'live_status'           => 'This field determines whether the member of public can search for and see your store on the marketplace.'
    ]
];