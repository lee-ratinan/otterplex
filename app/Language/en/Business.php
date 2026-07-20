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
        'new-user'                   => 'New User',
        'new-user-info'              => 'New User Information',
        'generic-info'               => 'Generic Information',
        'link-to-business'           => 'User Status in Business',
        'link-to-branches'           => 'User Status in Branches',
        'no-branches'                => 'The user is not linked to any branches, please add a new branch.',
        'link-to-new-branch'         => 'Add New Branch',
        'custom-attributes'          => 'Custom Attributes',
        'language-proficiency-level' => 'Language Proficiency Level',
        'new-language'               => 'New Language',
        'other-attributes'           => 'Other Attributes',
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
    ],
    'business-management'       => [
        'export'         => [
            'title'       => 'Export Business Data',
            'paragraph-1' => 'Export your business data in <code>.csv</code> format (Excel compatible).',
            'paragraph-2' => 'The data will be processed and will be emailed to your account’s email address, {0}, when it is ready.',
            'paragraph-3' => 'Please contact us if you do not receive an email within 2 working days.',
            'button'      => 'Submit Request'
        ],
        'delete'         => [
            'title'       => 'Delete Business',
            'paragraph-1' => 'After you make this request, your business will be <b>disabled</b> instantly, and you will be logged out of the system.',
            'paragraph-2' => 'Your data will be retained for a minimum of <b>30 days</b>, and will be deleted at the <b>end of next month</b>.',
            'paragraph-3' => 'If you wish to restore your business data and reactivate your account, please contact us before the <b>20th of next month</b> for guaranteed recovery.',
            'paragraph-4' => 'You may choose to export your data before you request your business deletion.',
            'button'      => 'Next',
        ],
        'delete-confirm' => [
            'title'       => 'Before you go',
            'paragraph-1' => 'We’re sorry to see you go, but we completely respect your decision.',
            'paragraph-2' => 'Thank you for choosing our system to support your business. We wish you and your team all the best in your next chapter.',
            'q-1'         => [
                'question'    => 'Why are you closing your business account?',
                'instruction' => '(Please select the reasons below)',
                'opt-1'       => 'The platform is missing specific features my business needs.',
                'opt-2'       => 'I found the system too complex or difficult to set up.',
                'opt-3'       => 'I am closing this business or pausing operations.',
                'opt-4'       => 'The pricing does not fit my current budget.',
                'opt-5'       => 'I am switching to an alternative service/software.',
                'opt-6'       => 'Technical issues or performance bugs.',
                'other'       => 'Other (Please specify briefly):'
            ],
            'q-2'         => [
                'question'    => 'Did you know cheaper options are available?',
                'instruction' => 'If your business needs have scaled down, you don’t have to delete your data completely. You can downgrade to a lower tier or our <b>Free Plan</b>.',
                'button'      => 'How to Downgrade',
                'ack'         => 'I know about the cheaper alternatives.'
            ],
            'q-3'         => [
                'question'    => 'Subscription & Refund Notice',
                'instruction' => 'Per the Subscription Agreement accepted at checkout, all purchases are final. Upon submitting this request, your access will be disabled immediately, and any remaining days on your current billing cycle are non-refundable and will not be prorated.',
                'ack'         => 'I understand that I am forfeiting the remaining time on my paid subscription and that no refunds will be issued.',
            ],
            'q-4'         => [
                'question' => 'How likely are you to recommend our system to another business owner in the future?',
                'at-0'     => 'Not at all likely',
                'at-10'    => 'Extremely likely',
            ],
            'buttons'     => [
                'cancel'  => 'Cancel & Keep Account',
                'confirm' => 'Submit Request & Log Out'
            ]
        ]
    ],
    'business-plan'             => [
        'pending-contract'      => 'You have unpaid invoice (invoice #{0}).',
        'estimated-expiry'      => 'Estimated Expiry Date',
        'timezone'              => 'Timezone',
        'expiry-note'           => 'The actual expiry date will be computed after the payment is confirmed.',
        'amount-due'            => 'Amount Due',
        'payment-methods'       => 'Available Payment Methods',
        'promptpay'             => 'Thailand’s PromptPay',
        'bank-transfer'         => 'Bank Transfer',
        'proceed'               => 'Proceed',
        'instruction-received'  => 'Successfully received the confirmation of the payment method!',
        'promptpay-qr'          => [
            'title'         => 'PromptPay QR Code',
            'instruction-1' => 'Scan the QR code below with your mobile device to complete the payment. Please send the proof of payment via the email or OtterNova LINE account to ensure that the payment is successful.',
            'instruction-2' => 'If your payment is not confirmed within 24 hours, please contact us for assistance.'
        ],
        'bank-transfer-section' => [
            'title'          => 'Bank Transfer',
            'instruction-1'  => 'Please send the proof of payment via the email or OtterNova LINE account to ensure that the payment is successful.',
            'instruction-2'  => 'If your payment is not confirmed within 24 hours, please contact us for assistance.',
            'bank-name'      => 'Bank Name',
            'swift-code'     => 'Swift Code',
            'account-name'   => 'Account Name',
            'account-number' => 'Account Number',
        ],
        'payment'               => [
            'instruction-for-pending-payment-confirmation' => 'The payment information is also sent to your email address. You can proceed to make the payment and close this page.'
        ]
    ]
];