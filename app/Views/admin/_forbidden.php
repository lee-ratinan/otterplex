<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center p-5">
                    <div class="display-1 p-5">
                        <i class="bi bi-exclamation-circle text-danger"></i>
                    </div>
                    <h2><?= lang('Admin.forbidden.title') ?></h2>
                    <p><?= lang('Admin.forbidden.paragraph') ?></p>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>