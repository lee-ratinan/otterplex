<?php
return [
    'welcome-message' => 'Welcome, {0}!',
    'setup'           => [
        'title'                => 'System Settings',
        'health-check'         => 'Health Check',
        'variant_count'        => '{0} variant(s)',
        'check-business-setup' => '<i class="fa-solid fa-circle-info text-warning"></i> Your business data is set during your sign up process. You may verify your business setup <a href="' . base_url('admin/business') . '">here</a>.',
        'branches-you-have'    => '<i class="fa-solid fa-circle-check text-success"></i> You currently have {0} branch(es).',
        'branches-you-dont'    => '<i class="fa-solid fa-circle-xmark text-danger"></i> You have no branches setup. Please setup your first branch.',
        'staff-you-have'       => '<i class="fa-solid fa-circle-check text-success"></i> You currently have {0} staff allocation(s). Please verify your staff allocation regularly to ensure accuracy.',
        'staff-you-dont'       => '<i class="fa-solid fa-circle-xmark text-danger"></i> You have no staff allocations setup for the branches. Please setup your first staff member to the branches.',
        'services-you-have'    => '<i class="fa-solid fa-circle-check text-success"></i> You currently have {0} service(s). Please verify your services regularly to ensure accuracy.',
        'services-you-dont'    => '<i class="fa-solid fa-circle-xmark text-danger"></i> You have no services setup. Please setup your first service.',
        'products-you-have'    => '<i class="fa-solid fa-circle-check text-success"></i> You currently have {0} product(s). Please verify your products regularly to ensure accuracy.',
        'products-you-dont'    => '<i class="fa-solid fa-circle-xmark text-danger"></i> You have no products setup. Please setup your first product.',
    ]
];