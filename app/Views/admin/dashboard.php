<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
<?php if (empty($session->business)) : ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center p-5">
                    <div class="display-1 p-5">
                        <i class="bi bi-exclamation-circle text-danger"></i>
                    </div>
                    <h2><?= lang('Admin.dashboard.no-business.title') ?></h2>
                    <p><?= lang('Admin.dashboard.no-business.paragraph', [base_url('/admin/my-businesses')]) ?></p>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
abcdef
<?php endif; ?>
<?php $this->endSection() ?>