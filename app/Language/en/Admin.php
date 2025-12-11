<?php
return [
    'site-name'     => 'OtterNova',
    'pages'         => [
        'dashboard'                 => 'Dashboard',
        'search'                    => 'Search',
        'profile'                   => 'Profile',
        'my-businesses'             => 'My Businesses',
        'about'                     => 'About',
        'logout'                    => 'Logout',
        'order'                     => 'Order',
        'allocation'                => 'Allocation',
        'allocation-staff'          => 'Staff Allocation',
        'allocation-resource'       => 'Resource Allocation',
        'service'                   => 'Service',
        'product'                   => 'Product',
        'product-category'          => 'Product Category',
        'review'                    => 'Review',
        'discount'                  => 'Discount',
        'resource'                  => 'Resource',
        'resource-type'             => 'Resource Type',
        'blog'                      => 'Blog',
        'blog-category'             => 'Blog Category',
        'business'                  => 'Business',
        'business-contract-renewal' => 'Business Contract Renewal',
        'business-branch'           => 'Branch',
        'business-branch-manage'    => 'Branch Manager',
        'business-user'             => 'Staff',
        'business-customer'         => 'Customer'
    ],
    'dashboard'     => [
        'no-business' => [
            'title'     => 'You have no active businesses',
            'paragraph' => 'There is no active businesses linked to your account. please go to <a href="{0}">My Businesses</a> to manage your business account(s).'
        ]
    ],
    'my-businesses' => [
        'title'        => 'All My Businesses',
        'you-are-here' => '<i class="bi bi-geo-alt"></i> You are here',
        'new-business' => 'New Business',
        'btn'          => [
            'manage'    => '<i class="bi bi-gear"></i> Manage this Business',
            'switch-to' => '<i class="bi bi-toggle2-on"></i> Switch to this Business',
        ]
    ],
    'forbidden'     => [
        'title'     => 'Not Permitted',
        'paragraph' => 'You do not have permission to access this page.',
    ],
    'profile'       => [
        'controlled-account-data'  => 'Controlled Data',
        'profile-data'             => 'Profile Data',
        'avatar'                   => 'Avatar',
        'upload-avatar'            => 'Upload Avatar',
        'upload-explanation'       => 'The system only accepts <code>png</code> and <code>jpg</code> files under 400kb. If the file is not square, it will be cropped to a square.',
        'change-password'          => 'Change Password',
        'new-password-requirement' => [
            'title'               => 'Password Strength Requirements:',
            'item-1'              => 'Password must be at least 8 characters long.',
            'item-2'              => 'Password must contain at least one number.',
            'item-3'              => 'Password must contain at least one uppercase letter.',
            'item-4'              => 'Password must contain at least one lowercase letter.',
            'item-5'              => 'Password must contain at least one special character: @$!%*?&',
            'item-6'              => 'Password must not contain first and/or family name(s).',
            'item-7'              => 'Password must not be too common.',
            'item-8'              => 'Password must not contain illegal letters.',
            'requirement-not-met' => 'Password requirements not met, please modify your password.',
        ],
    ],
    'about'         => [
        'about'          => [
            'title'     => '🦦 About OtterNova',
            'subtitle'  => 'Your command center for bookings, schedules, products, and all the operational magic.',
            'paragraph' => 'Many businesses still wrestle with paper notebooks, Excel sheets held together by tears, and Post-it notes that disappear at the worst possible moment. OtterNova exists to end that chaos. Your dashboard brings everything into one clean, connected, API-powered system — built for speed, accuracy, and zero nonsense.'
        ],
        'for'            => [
            'title'     => '🌟 What This Page Is For',
            'paragraph' => 'This page gives you a quick tour of the system, how things work, where to find help, and how to get the most out of the platform. If you get lost, don’t worry — the otters here don’t judge. They just guide.',
        ],
        'started'        => [
            'title'  => '📘 Getting Started',
            'step-1' => '<b>1. Your Workspace</b><br>Once you log in and select your business (which should be selected by default if you have set the default one), you’ll see your dashboard overview: today’s bookings, upcoming tasks, and shortcuts to the sections you use most.',
            'step-2' => '<b>2. Navigation at a Glance</b><br>
- Bookings – View, edit, reschedule, cancel, or create sessions.<br>
- Products & Services – Manage what you sell, how much it costs, and which branches offer it.<br>
- Businesses & Branches – Maintain your branch info, opening hours, and resource availability.<br>
- Customers – See customer profiles, booking history, and notes.<br>
- Staff – Assign roles and manage user access.',
            'step-3' => '<b>3. User Roles & Permissions</b><br>
Every user gets a role: Owner, Manager, Staff.<br>
Your current role decides what you can view or edit. If you need more access, poke your Admin (gently).'
        ],
        'security'       => [
            'title'     => '🧭 How the System Keeps You Safe',
            'paragraph' => 'We automatically check:<br>
- Whether you’re logged in<br>
- Whether your session is still valid<br>
- Whether you have permission to be on a page<br>
- Whether your actions are allowed by your role<br>
This keeps your data safe and your operations clean — unlike some other authoritarian corporates that love collecting your data without asking. Here, you stay in control.'
        ],
        'help'           => [
            'title'     => '🙋 Need Help?',
            'paragraph' => 'We built this platform to be intuitive, but nobody is perfect, not even otters. If you get stuck:<br>
- Look for tips in the interface<br>
- Check the Help Center links<br>
- Or contact your business’s assigned admin<br>
And if it’s something technical, your admin can forward it to your OtterNova support channel.'
        ],
        'best-practices' => [
            'title'     => '🔧 Best Practices',
            'paragraph' => '- Keep your branch hours updated<br>
- Review bookings daily<br>
- Regularly check staff roles and access<br>
- Use the built-in logs to track changes'
        ],
        'updates'        => [
            'title'     => '🚀 New Features & Updates',
            'paragraph' => 'You’ll see announcements when new modules, improvements, or integrations roll out. We keep things modern so your operations don’t get stuck in the last decade.'
        ],
        'final'          => [
            'title'     => '🦦 Final Words',
            'paragraph' => 'OtterNova is here to make your workflow smoother, faster, and future-ready.<br>
No chaos. No guessing. No dictatorship of messy spreadsheets.<br>
Just a clean system that works — so you can focus on running your business.'
        ]
    ]
];