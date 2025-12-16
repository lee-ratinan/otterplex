<?php $this->extend('home/_layout'); ?>
<?= $this->section('content') ?>
    <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4"><?= lang('System.pages.account-activation') ?></h5>
    </div>
    <div class="text-center">
        <?php if (empty($error)) : ?>
            <div class="display-1"><i class="fa-solid fa-circle-check text-success"></i></div>
            <p><?= lang('System.account-activation.success-message') ?></p>
            <p><a href="<?= base_url() ?>"><?= lang('System.pages.login') ?></a></p>
        <?php else : ?>
            <div class="display-1"><i class="fa-solid fa-circle-exclamation text-danger"></i></div>
            <p><?= lang('System.account-activation.error-message') ?></p>
            <p><?= lang('System.account-activation.errors.' . $error) ?></p>
        <?php endif; ?>
    </div>
<?php $this->endSection() ?>