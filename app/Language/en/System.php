<?php
return [
    'site-name'       => 'OtterNova System',
    'change-language' => 'Change Language',
    'go-to-main-site' => 'Go to main site',
    'pages'           => [
        'login'           => 'Login',
        'create-account'  => 'Create an Account',
        'forgot-password' => 'Forgot Password',
        'reset-password'  => 'Reset Password',
    ],
    'login'           => [
        'title'             => 'Login',
        'instruction'       => 'Please login to your account.',
        'fields'            => [
            'username'             => 'Username (email address)',
            'username-empty-error' => 'Please enter your username.',
            'password'             => 'Password',
            'password-empty-error' => 'Please enter your password.',
        ],
        'remember-me'       => 'Remember me',
        'dont-have-account' => 'Don’t have an account?',
    ],
    'create-account'  => [
        'instruction'          => 'Enter your personal details to create account',
        'fields'               => [
            'full-name'             => 'Full name',
            'full-name-empty-error' => 'Please enter your full name.',
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
        'instruction' => 'Please enter your new password',
        'fields'      => [
            'password'                     => 'New Password',
            'password-confirm'             => 'Confirm Password',
            'password-empty-error'         => 'Please enter your password.',
            'password-confirm-empty-error' => 'Please enter your confirm password.',
        ],
        'change-password' => 'Change Password',
    ]
];
