<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
<?php if (empty($session->business)) : ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body text-center p-5">
                    <div class="display-1 p-5">
                        <i class="fa-solid fa-circle-exclamation text-danger"></i>
                    </div>
                    <h2><?= lang('Admin.dashboard.no-business.title') ?></h2>
                    <p><?= lang('Admin.dashboard.no-business.paragraph', [base_url('/admin/my-businesses')]) ?></p>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="row">
        <div class="col">
            <h1 class="mb-5"><?= lang('Dashboard.welcome-message', [$session->full_name]) ?></h1>
        </div>
    </div>
    <?php if (isset($dashboard['setup'])) : ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= lang('Dashboard.setup.title') ?></h2>
                    <h3><?= lang('Dashboard.setup.health-check') ?></h3>
                    <div class="row g-3">
                        <!-- BUSINESS -->
                        <div class="col-12 col-lg-6">
                            <div class="bg-light rounded-3 p-3">
                                <p><?= lang('Dashboard.setup.check-business-setup') ?></p>
                            </div>
                        </div>
                        <!-- BRANCHES -->
                        <div class="col-12 col-lg-6">
                            <div class="bg-light rounded-3 p-3">
                                <?php if (0 < count($dashboard['setup']['branches'])) : ?>
                                    <p><?= lang('Dashboard.setup.branches-you-have', [count($dashboard['setup']['branches'])]) ?></p>
                                    <?php
                                    foreach ($dashboard['setup']['branches'] as $branch) {
                                        $local_names = json_decode($branch['branch_local_names'], true);
                                        $branch_name = $local_names[$session->lang] ?? $branch['branch_name'];
                                        echo '<a class="btn btn-outline-primary btn-sm m-1" href="' . base_url('admin/business/branch/' . $branch['branch_slug']) . '">' . $branch_name . '</a> ';
                                    }
                                    ?>
                                <?php else: ?>
                                    <p><?= lang('Dashboard.setup.branches-you-dont') ?></p>
                                <?php endif ?>
                                <a href="<?= base_url('admin/business/branch') ?>" class="btn btn-primary btn-sm m-1"><i class="fa-solid fa-gear"></i> <?= lang('Admin.pages.business-branch') ?></a>
                            </div>
                        </div>
                        <!-- STAFF -->
                        <div class="col-12 col-lg-6">
                            <div class="bg-light rounded-3 p-3">
                                <?php if (0 < count($dashboard['setup']['staff'])) : ?>
                                    <p><?= lang('Dashboard.setup.staff-you-have', [count($dashboard['setup']['staff'])]) ?></p>
                                <?php else: ?>
                                    <p><?= lang('Dashboard.setup.staff-you-dont') ?></p>
                                <?php endif ?>
                                <a href="<?= base_url('admin/business/user') ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-gear"></i> <?= lang('Admin.pages.business-user') ?></a>
                            </div>
                        </div>
                        <!-- SERVICE -->
                        <div class="col-12 col-lg-6">
                            <div class="bg-light rounded-3 p-3">
                                <?php if (0 < count($dashboard['setup']['services'])) : ?>
                                    <p><?= lang('Dashboard.setup.services-you-have', [count($dashboard['setup']['services'])]) ?></p>
                                    <?php
                                    foreach ($dashboard['setup']['services'] as $id => $info) {
                                        $count = count($info['variants']);
                                        echo '<a class="btn btn-outline-' . (0 == $count ? 'danger' : 'primary') . ' btn-sm m-1" href="' . base_url('admin/service/' . $id) . '">' . $info['service_name'] . ': ' . lang('Dashboard.setup.variant_count', [$count]) . '</a> ';
                                    }
                                    ?>
                                <?php else: ?>
                                    <p><?= lang('Dashboard.setup.services-you-dont') ?></p>
                                <?php endif ?>
                                <a href="<?= base_url('admin/service') ?>" class="btn btn-primary btn-sm m-1"><i class="fa-solid fa-gear"></i> <?= lang('Admin.pages.service') ?></a>
                            </div>
                        </div>
                        <!-- PRODUCT -->
                        <div class="col-12 col-lg-6">
                            <div class="bg-light rounded-3 p-3">
                                <?php if (0 < count($dashboard['setup']['products'])) : ?>
                                    <p><?= lang('Dashboard.setup.products-you-have', [count($dashboard['setup']['products'])]) ?></p>
                                    <?php
                                    foreach ($dashboard['setup']['products'] as $id => $info) {
                                        $count = count($info['variants']);
                                        echo '<a class="btn btn-outline-' . (0 == $count ? 'danger' : 'primary') . ' btn-sm m-1" href="' . base_url('admin/product/' . $id) . '">' . $info['product_name'] . ': ' . lang('Dashboard.setup.variant_count', [$count]) . '</a> ';
                                    }
                                    ?>
                                <?php else: ?>
                                    <p><?= lang('Dashboard.setup.products-you-dont') ?></p>
                                <?php endif ?>
                                <a href="<?= base_url('admin/product') ?>" class="btn btn-primary btn-sm m-1"><i class="fa-solid fa-gear"></i> <?= lang('Admin.pages.product') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="row d-none">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    other dashboards
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php $this->endSection() ?>