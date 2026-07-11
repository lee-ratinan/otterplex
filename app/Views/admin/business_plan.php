<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= lang('Admin.pages.business-plan') ?></h2>

                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>