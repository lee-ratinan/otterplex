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
    <link href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/boxicons/css/boxicons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/quill/quill.snow.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/quill/quill.bubble.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/remixicon/remixicon.css') ?>" rel="stylesheet">
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
            <?= (!empty($businessName) ? $businessName . ' @ ' : '') ?>
            <?= lang('Admin.site-name') ?>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <!-- Header / Search Bar -->
    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="GET" action="<?= base_url('/admin/search') ?>">
            <input type="search" name="q" placeholder="<?= lang('Admin.pages.search') ?>" title="<?= lang('Admin.pages.search') ?>">
            <button type="submit" id="btn-search" title="<?= lang('Admin.pages.search') ?>"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle" href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li>
            <!-- Header / Notifications -->
            <li class="nav-item dropdown d-none">
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
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
                    <li><a class="dropdown-item d-flex align-items-center" href="<?= base_url('/admin/profile') ?>"><i class="bi bi-person-fill-gear me-3"></i><span><?= lang('Admin.pages.profile') ?></span></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="<?= base_url('/admin/my-businesses') ?>"><i class="bi bi-shop-window me-3"></i><span><?= lang('Admin.pages.my-businesses') ?></span></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="<?= base_url('/admin/about') ?>"><i class="bi bi-info-circle me-3"></i><span><?= lang('Admin.pages.about') ?></span></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="<?= base_url('/logout') ?>"><i class="bi bi-box-arrow-right me-3"></i><span><?= lang('Admin.pages.logout') ?></span></a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item"><a class="nav-link <?= ('dashboard' == $slug ? '' : 'collapsed' ) ?>" href="<?= base_url('/admin/dashboard') ?>"><i class="bi bi-house-door me-3"></i><span><?= lang('Admin.pages.dashboard') ?></span></a></li>
        <!-- AREA FOR OTHER MENU -->




        <!-- BUSINESSES+BRANCHES -->
        <?php if (isset($session->permitted_features['branch-management'])): ?>
            <li class="nav-item">
                <a class="nav-link <?= (in_array($slug, ['businesses', 'branches', 'business-settings', 'resource-types', 'resources', 'staff', 'staff-types']) ? '' : 'collapsed' ) ?>" data-bs-target="#branches-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-shop me-3"></i><span><?= lang('Admin.nav.businesses-and-branches') ?></span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="branches-nav" class="nav-content collapse <?= (in_array($slug, ['businesses', 'branches', 'business-settings', 'resource-types', 'resources', 'staff', 'staff-types']) ? 'show' : '' ) ?>" data-bs-parent="#sidebar-nav" style="">
                    <li><a class="<?= ('businesses' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/businesses') ?>"><i class="bi bi-circle"></i><span><?= lang('Admin.pages.businesses') ?></span></a></li>
                    <li><a class="<?= ('branches' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/branches') ?>"><i class="bi bi-circle ms-3"></i><span><?= lang('Admin.pages.branches') ?></span></a></li>
                    <li><a class="<?= ('business-settings' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/business-settings') ?>"><i class="bi bi-circle ms-3"></i><span><?= lang('Admin.pages.business-settings') ?></span></a></li>
                    <li><a class="<?= ('resources' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/resources') ?>"><i class="bi bi-circle"></i><span><?= lang('Admin.pages.resources') ?></span></a></li>
                    <li><a class="<?= ('resource-types' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/resource-types') ?>"><i class="bi bi-circle ms-3"></i><span><?= lang('Admin.pages.resource-types') ?></span></a></li>
                    <li><a class="<?= ('staff' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/staff') ?>"><i class="bi bi-circle"></i><span><?= lang('Admin.pages.staff') ?></span></a></li>
                    <li><a class="<?= ('staff-types' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/staff-types') ?>"><i class="bi bi-circle ms-3"></i><span><?= lang('Admin.pages.staff-types') ?></span></a></li>
                </ul>
            </li>
        <?php endif; ?>
        <!-- SERVICES -->
        <?php if (isset($session->permitted_features['service-management'])): ?>
            <li class="nav-item">
                <a class="nav-link <?= (in_array($slug, ['services', 'variants']) ? '' : 'collapsed' ) ?>" data-bs-target="#services-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-lightbulb me-3"></i><span><?= lang('Admin.nav.services') ?></span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="services-nav" class="nav-content collapse <?= (in_array($slug, ['services', 'variants']) ? 'show' : '' ) ?>" data-bs-parent="#sidebar-nav" style="">
                    <li><a class="<?= ('services' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/services') ?>"><i class="bi bi-circle"></i><span><?= lang('Admin.pages.services') ?></span></a></li>
                    <li><a class="<?= ('variants' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/service-variants') ?>"><i class="bi bi-circle ms-3"></i><span><?= lang('Admin.pages.variants') ?></span></a></li>
                </ul>
            </li>
        <?php endif; ?>
        <!-- PRODUCTS -->
        <?php if (isset($session->permitted_features['product-management'])): ?>
            <li class="nav-item">
                <a class="nav-link <?= (in_array($slug, ['products', 'product-variants', 'product-categories', 'product-variant-keys']) ? '' : 'collapsed' ) ?>" data-bs-target="#products-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-box-seam me-3"></i><span><?= lang('Admin.nav.products') ?></span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="products-nav" class="nav-content collapse <?= (in_array($slug, ['products', 'product-variants', 'product-categories', 'product-variant-keys']) ? 'show' : '' ) ?>" data-bs-parent="#sidebar-nav" style="">
                    <li><a class="<?= ('products' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/products') ?>"><i class="bi bi-circle"></i><span><?= lang('Admin.pages.products') ?></span></a></li>
                    <li><a class="<?= ('product-variants' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/product-variants') ?>"><i class="bi bi-circle ms-3"></i><span><?= lang('Admin.pages.product-variants') ?></span></a></li>
                    <li><a class="<?= ('product-categories' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/product-categories') ?>"><i class="bi bi-circle ms-3"></i><span><?= lang('Admin.pages.product-categories') ?></span></a></li>
                    <li><a class="<?= ('product-variant-keys' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/product-variant-keys') ?>"><i class="bi bi-circle ms-3"></i><span><?= lang('Admin.pages.product-variant-keys') ?></span></a></li>
                </ul>
            </li>
        <?php endif; ?>
        <!-- BLOG MASTER -->
        <?php if (isset($session->permitted_features['blog'])): ?>
            <li class="nav-item">
                <a class="nav-link <?= (in_array($slug, ['blog', 'blog-categories']) ? '' : 'collapsed' ) ?>" data-bs-target="#blog-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-pen me-3"></i><span><?= lang('Admin.nav.blog') ?></span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="blog-nav" class="nav-content collapse <?= (in_array($slug, ['blog', 'blog-categories']) ? 'show' : '' ) ?>" data-bs-parent="#sidebar-nav" style="">
                    <li><a class="<?= ('blog' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/blog') ?>"><i class="bi bi-circle"></i><span><?= lang('Admin.pages.blog') ?></span></a></li>
                    <li><a class="<?= ('blog-categories' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/blog-categories') ?>"><i class="bi bi-circle ms-3"></i><span><?= lang('Admin.pages.blog-categories') ?></span></a></li>
                </ul>
            </li>
        <?php endif; ?>
        <!-- USER MASTER -->
        <?php if (isset($session->permitted_features['users'])): ?>
            <li class="nav-item">
                <a class="nav-link <?= (in_array($slug, ['users', 'roles']) ? '' : 'collapsed' ) ?>" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people me-3"></i><span><?= lang('Admin.nav.users-and-roles') ?></span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="users-nav" class="nav-content collapse <?= (in_array($slug, ['users', 'roles']) ? 'show' : '' ) ?>" data-bs-parent="#sidebar-nav" style="">
                    <li><a class="<?= ('users' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/users') ?>"><i class="bi bi-circle"></i><span><?= lang('Admin.pages.users') ?></span></a></li>
                    <li><a class="<?= ('roles' == $slug ? 'active' : '' ) ?>" href="<?= base_url('/admin/roles') ?>"><i class="bi bi-circle ms-3"></i><span><?= lang('Admin.pages.roles') ?></span></a></li>
                </ul>
            </li>
        <?php endif; ?>
        <?php if (isset($session->permitted_features['site-configurations'])): ?>
            <li class="nav-item"><a class="nav-link <?= ('site-configurations' == $slug ? '' : 'collapsed' ) ?>" href="<?= base_url('/admin/site-configurations') ?>"><i class="bi bi-gear-wide-connected me-3"></i><span><?= lang('Admin.pages.site-configurations') ?></span></a></li>
        <?php endif; ?>
    </ul>
</aside>
<!-- MAIN -->
<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= lang('Admin.pages.' . $slug) ?></h1>
        <nav>
            <ol class="breadcrumb">
                <i class="bi bi-house-door me-2"></i>
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
            Session Expiry : <?= $session->sessionExpiry ?> VS now : <?= date(DATETIME_FORMAT_DB) ?> UTC - <a href="?hl=th">ภาษาไทย</a> - <a href="?hl=en">English</a>
        </small>
    </div>
</footer>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
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