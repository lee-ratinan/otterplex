<?php
return [
    'title'                     => 'Manage Business: {0}',
    'subtitle'                  => [
        'generic-information' => 'Generic Information',
        'tax-information'     => 'Tax Information',
        'mart-decoration'     => 'Your Marketplace Decoration',
        'mart-seo'            => 'SEO Information',
    ],
    'btn-update-slug'           => 'Update Slug',
    'btn-update-slug-confirm'   => 'Confirm Update',
    'marketplace-url'           => 'The URL to your marketplace is <a href="{0}" target="_blank">{0}</a>. If you have changed the slug, the URL will be changed once you clicked “Save” below.',
    'logo'                      => 'Logo',
    'upload-logo'               => 'Upload Your Business Logo',
    'upload-explanation'        => 'The system only accepts <code>png</code> and <code>jpg</code> files under 600kb. If the file is larger than 500px (width), 500px (height), it will be cropped accordingly.',
    'business-header'           => 'Business Header Banner',
    'upload-header-img'         => 'Upload Your Business Header Banner',
    'upload-explanation-header' => 'The system only accepts <code>png</code> and <code>jpg</code> files under 1000kb. If the file is larger than 1200px (width), 800px (height), it will be cropped accordingly.',
    'marketplace'               => 'Marketplace',
    'marketplace-example-text'  => 'This is the example of the text to be shown on your marketplace website.',
    'clear-cache'               => 'Your change will be reflected on the marketplace within an hour. If you would like to update it now, please click the following button',
    'btn-clear-cache'           => 'Clear Cache',
    'contracts'                 => 'Contracts',
    'contract-renew'            => 'Upgrade/Renew Plan',
    'payment-records'           => 'Payment Records',
    'packages'                  => [
        'pick-one' => 'Please select a package to renew',
        'validity' => [
            'month' => 'Monthly Package',
            'year'  => 'Yearly Package',
        ]
    ],
    'has-unpaid-contract'       => 'You have an unpaid contract, please make a payment.',
    'renewal'                   => [
        'amount-due'         => 'Amount Due',
        'payments'           => 'Payment Records',
        'payment-at'         => 'Recorded At',
        'how-to-pay'         => 'How to Pay',
        'pay-by-credit-card' => 'Pay by Credit Card',
        'pay-by-qr-thailand' => 'Pay by PromptPay QR Code (Thailand)',
    ],
    'branch-management'         => [
        'new-branch'           => 'New Branch',
        'generic-title'        => 'New Branch Information',
        'generic-information'  => 'Generic Information',
        'opening-hours'        => 'Opening Hours',
        'modified-hours'       => 'Modified Hours',
        'modified-hours-new'   => 'Add Date with Modified Hours',
        'hours'                => [
            'day'    => 'Day',
            'opens'  => 'Opens',
            'closes' => 'Closes',
        ],
        'days'                 => [
            'M'  => 'Monday',
            'T'  => 'Tuesday',
            'W'  => 'Wednesday',
            'TH' => 'Thursday',
            'F'  => 'Friday',
            'S'  => 'Saturday',
            'SU' => 'Sunday',
        ],
        'close-shop'           => 'Close Shop',
        'staff-in-this-branch' => 'Staff In This Branch',
        'go-to-user-to-manage' => 'Please go to <a href="{0}">staff management</a> to update this list.'
    ],
    'user-management'           => [
        'new-user'           => 'New User',
        'new-user-info'      => 'New User Information',
        'generic-info'       => 'Generic Information',
        'link-to-business'   => 'User Status in Business',
        'link-to-branches'   => 'User Status in Branches',
        'no-branches'        => 'The user is not linked to any branches, please add a new branch.',
        'link-to-new-branch' => 'Add New Branch'
    ],
    'user-attribute'            => [
        'title'         => 'User Attribute',
        'paragraph'     => 'By default, there are some user attributes that the system supports by default, such as name, gender, nationality, language skills. However, if you wish to add more attributes, you can do so by defining more on this page. The fields will be shown on the user profile page. However, you cannot remove the attribute once saved. You can only hide them if you do not need them anymore.',
        'paragraph-2'   => 'You can add up to {0} attributes.',
        'new-attribute' => 'New Attribute',
    ],
    'customer-management'       => [
        'privacy-policy' => 'Customer data is considered sensitive information. The customers have their rights to modify and/or delete their information at any given time.',
    ],
    'resource-management'       => [
        'new-resource'            => 'New Resource',
        'new-resource-type'       => 'New Resource Type',
        'add-resource-type-first' => 'Please add a resource type before creating a new resource.',
    ],
    'shipping-rate'             => [
        'add-new'             => 'Add New Shipping Rate',
        'max-price-error'     => 'The upper limit of the range must be higher than the lower limit.',
        'shipping-rate-error' => 'Shipping rate must be a positive number.'
    ]
];