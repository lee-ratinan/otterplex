<?php
$session      = session();
$businessName = '';
if (!empty($session->business)) {
    $businessName = $session->business['business_local_names'][$lang] ?? $session->business['business_name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>
        <?= lang('Admin.pages.' . $slug) ?> |
        <?= (!empty($businessName) ? $businessName . ' | ' : '') ?>
        <?= lang('Admin.site-name') ?>
    </title>
    <!-- Favicons -->
    <link href="<?= base_url('assets/img/favicon.png') ?>" rel="icon">
    <link href="<?= base_url('assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <?php if ('th' == $lang) : ?>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Noto+Serif+Thai:wght@100..900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <?php else : ?>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <?php endif; ?>
    <!-- Vendor CSS Files -->
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/fontawesome-free-7.1.0/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/quill/quill.snow.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/quill/quill.bubble.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/simple-datatables/style.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/toastrjs/toastr.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/DataTables/datatables.min.css') ?>" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
    <!-- =======================================================
    * Template Name: NiceAdmin
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Updated: Apr 20 2024 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>
<body class="lang-<?= $lang ?>">
<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="<?= base_url('/admin/dashboard') ?>" class="logo d-flex align-items-center">
            <?php if (!empty($session->business_logo)) : ?>
                <img src="<?= $session->business_logo ?>" alt="<?= (!empty($businessName) ? $businessName : 'OtterNova Business') ?>" class="img-fluid">
            <?php endif; ?>
            <?= (!empty($businessName) ? $businessName . ' @ ' : '') ?>
            <?= lang('Admin.site-name') ?>
        </a>
        <i class="fa-solid fa-bars toggle-sidebar-btn"></i>
    </div>
    <!-- Header / Search Bar -->
    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="GET" action="<?= base_url('/admin/search') ?>">
            <input type="search" name="q" placeholder="<?= lang('Admin.pages.search') ?>" title="<?= lang('Admin.pages.search') ?>">
            <button type="submit" id="btn-search" title="<?= lang('Admin.pages.search') ?>"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle" href="#">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
            </li>
            <!-- Header / Notifications -->
            <li class="nav-item dropdown d-none">
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-bell"></i>
                    <span class="badge bg-primary badge-number">4</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-footer">###</li>
                </ul>
            </li>
            <!-- Header / Profile -->
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <?= $session->avatar ?>
                    <span class="d-none d-md-block dropdown-toggle ps-2"><?= $session->full_name ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?= $session->full_name ?></h6>
                        <span><?= substr($session->user['profile_status_msg'], 0, 30) . (empty($businessName) ? '' : '<br>' . $businessName . ' / ' . lang('BusinessUser.enum.user_role.' . $session->user_role)) ?></span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="<?= base_url('/admin/profile') ?>"><i class="fa-solid fa-user-gear me-3"></i><span><?= lang('Admin.pages.profile') ?></span></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="<?= base_url('/admin/my-businesses') ?>"><i class="fa-solid fa-store me-3"></i><span><?= lang('Admin.pages.my-businesses') ?></span></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="<?= base_url('/admin/about') ?>"><i class="fa-solid fa-circle-info me-3"></i><span><?= lang('Admin.pages.about') ?></span></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="<?= base_url('/logout') ?>"><i class="fa-solid fa-arrow-right-from-bracket me-3"></i><span><?= lang('Admin.pages.logout') ?></span></a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item"><a class="nav-link <?= ('dashboard' == $slug ? '' : 'collapsed' ) ?>" href="<?= base_url('/admin/dashboard') ?>"><i class="fa-solid fa-house me-3"></i><span><?= lang('Admin.pages.dashboard') ?></span></a></li>
        <?php
        $sidebar_menu = [];
        // ORDER & ALLOCATION
        $sidebar_menu['order'] = [
            'title' => '<i class="fa-solid fa-bag-shopping me-3"></i> <span>' . lang('Admin.pages.order') . '</span>',
            'links' => [
                'order' => [base_url('/admin/order'), lang('Admin.pages.order')],
            ]
        ];
        $sidebar_menu['allocation'] = [
            'title' => '<i class="fa-solid fa-calendar-check me-3"></i> <span>' . lang('Admin.pages.allocation') . '</span>',
            'links' => [
                'allocation-staff'    => [base_url('/admin/allocation/staff'), lang('Admin.pages.allocation-staff')],
                'allocation-resource' => [base_url('/admin/allocation/resource'), lang('Admin.pages.allocation-resource')],
            ]
        ];
        // PRODUCT/SERVICE/BLOG
        if (in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            $sidebar_menu['service'] = [
                'title' => '<i class="fa-solid fa-lightbulb me-3"></i> <span>' . lang('Admin.pages.service') . '</span>',
                'links' => [
                    'service' => [base_url('/admin/service'), lang('Admin.pages.service')],
                ]
            ];
            $sidebar_menu['product'] = [
                'title' => '<i class="fa-solid fa-box-open me-3"></i> <span>' . lang('Admin.pages.product') . '</span>',
                'links' => [
                    'product' => [base_url('/admin/product'), lang('Admin.pages.product')],
                    'product-category' => [base_url('admin/product/category'), lang('Admin.pages.product-category')],
                ]
            ];
//            $sidebar_menu['review'] = [
//                'title' => '<i class="fa-regular fa-comment-dots"></i> <span>' . lang('Admin.pages.review') . '</span>',
//                'links' => [
//                    'review' => [base_url('/admin/review'), lang('Admin.pages.review')],
//                ]
//            ];
//            $sidebar_menu['discount'] = [
//                'title' => '<i class="fa-solid fa-percent"></i> <span>' . lang('Admin.pages.discount') . '</span>',
//                'links' => [
//                    'discount' => [base_url('/admin/discount'), lang('Admin.pages.discount')],
//                ]
//            ];
//            $sidebar_menu['blog'] = [
//                'title' => '<i class="fa-solid fa-file-pen"></i> <span>' . lang('Admin.pages.blog') . '</span>',
//                'links' => [
//                    'blog' => [base_url('/admin/blog'), lang('Admin.pages.blog')],
//                    'blog-category' => [base_url('/admin/blog/category'), lang('Admin.pages.blog-category')],
//                ]
//            ];
        }
        // BUSINESS
        if ('OWNER' == $session->user_role) {
            $sidebar_menu['business'] = [
                'title' => '<i class="fa-solid fa-store me-3"></i><span>' . lang('Admin.pages.business') . '</span>',
                'links' => [
                    'business'          => [base_url('/admin/business'), lang('Admin.pages.business')],
                    'business-branch'   => [
                        base_url('/admin/business/branch'), lang('Admin.pages.business-branch')
                    ],
                    'business-user'     => [base_url('/admin/business/user'), lang('Admin.pages.business-user')],
                    'business-customer' => [
                        base_url('/admin/business/customer'), lang('Admin.pages.business-customer')
                    ],
                    'business-resource-type' => [base_url('/admin/resource/type'), lang('Admin.pages.business-resource-type')],
                    'business-resource'      => [base_url('/admin/resource'), lang('Admin.pages.business-resource')],
                ]
            ];
        } elseif ('MANAGER' == $session->user_role) {
            $sidebar_menu['business'] = [
                'title' => '<i class="fa-solid fa-store me-3"></i><span>' . lang('Admin.pages.business') . '</span>',
                'links' => [
                    'business-branch'   => [
                        base_url('/admin/business/branch'), lang('Admin.pages.business-branch')
                    ],
                    'business-user'     => [base_url('/admin/business/user'), lang('Admin.pages.business-user')],
                    'business-customer' => [
                        base_url('/admin/business/customer'), lang('Admin.pages.business-customer')
                    ],
                ]
            ];
        }
        // RENDER MENU
        foreach ($sidebar_menu as $group_key => $item) {
            echo '<li class="nav-item">
                <a class="nav-link ' . (str_starts_with($slug, $group_key) ? '' : 'collapsed' ) . '" data-bs-target="#' . $group_key . '-nav" data-bs-toggle="collapse" href="#">
                    ' . $item['title'] . '<i class="fa-solid fa-chevron-down ms-auto"></i>
                </a><ul id="' . $group_key . '-nav" class="nav-content collapse ' . (str_starts_with($slug, $group_key) ? 'show' : '' ) . '" data-bs-parent="#sidebar-nav" style="">';
            foreach ($item['links'] as $link_slug => $link) {
                echo '<li><a class="' . ($link_slug == $slug ? 'active' : '' ) . '" href="' . $link[0] . '"><i class="fa-solid fa-circle ms-3"></i><span>' . $link[1] . '</span></a></li>';
            }
            echo '</ul></li>';
        }
        ?>
    </ul>
</aside>
<!-- MAIN -->
<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= lang('Admin.pages.' . $slug) ?></h1>
        <nav>
            <ol class="breadcrumb">
                <i class="fa-solid fa-house me-2"></i>
                <?php if ('dashboard' != $slug) : ?>
                    <li class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>"><?= lang('Admin.pages.dashboard') ?></a></li>
                <?php endif; ?>
                <?php if (isset($breadcrumb) && is_array($breadcrumb)) : ?>
                    <?php foreach ($breadcrumb as $item) : ?>
                        <li class="breadcrumb-item"><a href="<?= $item['url'] ?>"><?= $item['page_title'] ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <li class="breadcrumb-item active"><?= lang('Admin.pages.' . $slug) ?></li>
            </ol>
        </nav>
    </div>
    <?= $this->renderSection('content') ?>
</main>
<!-- FOOTER -->
<footer id="footer" class="footer">
    <div class="copyright">
        <?= lang('System.copyrights', [date('Y')]) ?>
        <br>
        <small>
            VERSION <?= VERSIONING_NO ?> | RELEASED <?= format_date(VERSIONING_DT) ?>
            <br>
            Session Expiry : <?= $session->sessionExpiry ?> VS now : <?= date(DATETIME_FORMAT_DB) ?> UTC - <a href="?hl=th">ภาษาไทย</a> - <a href="?hl=en">English</a>
        </small>
    </div>
</footer>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="fa-solid fa-circle-up"></i></a>
<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/apexcharts/apexcharts.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/chart.js/chart.umd.js') ?>"></script>
<script src="<?= base_url('assets/vendor/echarts/echarts.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/quill/quill.js') ?>"></script>
<script src="<?= base_url('assets/vendor/simple-datatables/simple-datatables.js') ?>"></script>
<script src="<?= base_url('assets/vendor/tinymce/tinymce.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/php-email-form/validate.js') ?>"></script>
<script src="<?= base_url('assets/vendor/toastrjs/toastr.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/Luxon/luxon.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/DataTables/datatables.min.js') ?>"></script>
<!-- Template Main JS File -->
<script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>
</html>