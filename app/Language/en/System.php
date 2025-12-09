<?php
return [
    'site-name'       => 'OtterNova System',
    'copyrights'      => 'Copyrights &copy; 2025-{0} <b>OtterNova</b> All rights reserved.',
    'change-language' => 'Change Language',
    'go-to-main-site' => 'Go to main site',
    'pages'           => [
        'login'           => 'Login',
        'create-account'  => 'Create an Account',
        'forgot-password' => 'Forgot Password',
        'reset-password'  => 'Reset Password',
    ],
    'response-msg'    => [
        'error'   => [
            'generic'                   => 'Sorry, something went wrong. Please try again later.',
            'please-check-empty-field'  => 'Please check required field.',
            'password-does-not-matched' => 'Sorry, the confirm password does not match.',
            'password-failed'           => 'Sorry, failed to update your password.',
            'wrong-credentials'         => 'Your username or password is incorrect, please try again.',
            'inactive-account'          => 'Your account is not active. Please contact your administrator or email the support team.',
            'not-logged-in'             => 'Please login to continue.',
            'session-expired'           => 'Your session has expired. Please login to continue.',
            'business-inactive'         => 'You cannot switch to this business.',
            'db-issue'                  => 'Your data could not be saved at the moment. Please try again later.',
            'upload-failed'             => 'Sorry, the file failed to be uploaded.',
            'removed'                   => 'Sorry, the file failed to be removed.',
        ],
        'success' => [
            'business-switched' => 'You have successfully switched your business.',
            'data-saved'        => 'Your data has been saved.',
            'password-changed'  => 'Your password has been changed.',
            'uploaded'          => 'Your file has been uploaded.',
            'removed'           => 'Your file has been removed.',
        ]
    ],
    'buttons'         => [
        'new'             => 'New',
        'edit'            => 'Edit',
        'save'            => 'Save',
        'upload'          => 'Upload',
        'remove'          => 'Remove',
        'remove-confirm'  => 'Remove Confirmation',
        'switch-role'     => 'Switch Role',
        'switch-business' => 'Switch Business',
        'filter'          => 'Filter',
        'reset'           => 'Reset',
        'view-more'       => 'View More',
    ],
    'generic-term'    => [
        'no-data' => 'No data'
    ],
    'login'           => [
        'title'             => 'Login',
        'instruction'       => 'Please login to your account.',
        'fields'            => [
            'username'             => 'Username (email address)',
            'username-empty-error' => 'Please enter your username (email address).',
            'password'             => 'Password',
            'password-empty-error' => 'Please enter your password.',
        ],
        'dont-have-account' => 'Don’t have an account?',
    ],
    'create-account'  => [
        'instruction'          => 'Enter your personal details to create account',
        'fields'               => [
            'full-name'             => 'Full name',
            'full-name-empty-error' => 'Please enter your full name.',
            'first-name'            => 'First name',
            'last-name'             => 'Last name',
            'username'              => 'Username (email address)',
            'username-empty-error'  => 'Please enter your username (email address).',
            'password'              => 'Password',
            'password-empty-error'  => 'Please enter your password.',
            'plan'                  => 'Select your plan',
            'plan-empty-error'      => 'Please select your plan.',
            'plan-options'          => [
                'basic'    => 'Basic',
                'standard' => 'Standard',
                'premium'  => 'Premium',
            ],
            'i-agree'               => 'I agree to the ',
            'terms'                 => 'Terms & Conditions',
            'terms-error'           => 'You must agree before creating your account.'
        ],
        'already-have-account' => 'Already have an account?',
    ],
    'forgot-password' => [
        'instruction'    => 'Please enter your email address and follow the instructions on how to reset your password.',
        'email-address'  => 'Email address',
        'submit'         => 'Reset Password',
        'submit-success' => 'Your reset password request has been received. Please check your email and click the link to reset your password within 5 minutes.',
        'submit-error'   => 'An error has occurred, please try again later.'
    ],
    'reset-password'  => [
        'instruction'     => 'Please enter your new password',
        'fields'          => [
            'password'                     => 'New Password',
            'password-confirm'             => 'Confirm Password',
            'password-empty-error'         => 'Please enter your password.',
            'password-confirm-empty-error' => 'Please enter your confirm password.',
        ],
        'change-password' => 'Change Password',
        'token-invalid'   => 'Your token is invalid or expired, please start over again.'
    ]
];
