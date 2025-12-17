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
    <?php if (isset($dashboard['setup'])) : ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= lang('Dashboard.setup.title') ?></h2>
                    <h3><?= lang('Dashboard.setup.health-check') ?></h3>
                    <p><?= lang('Dashboard.setup.check-business-setup') ?></p>
                    <p><?= lang('Dashboard.setup.check-branches-setup') ?></p>
                    <p><?= lang('Dashboard.setup.check-staff-setup') ?></p>
                    <p><?= lang('Dashboard.setup.check-services-setup') ?></p>
                    <p><?= lang('Dashboard.setup.check-products-setup') ?></p>
                    <pre><?php print_r($dashboard['setup']); ?></pre>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
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