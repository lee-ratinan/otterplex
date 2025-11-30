<?php $this->extend('home/_layout'); ?>
<?= $this->section('content') ?>
    <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4"><?= lang('System.pages.forgot-password') ?></h5>
        <p class="text-center small"><?= lang('System.forgot-password.instruction') ?></p>
    </div>
    <form class="row g-3 needs-validation" novalidate="">
        <div class="col-12">
            <label for="username" class="form-label"><?= lang('System.login.fields.username') ?></label>
            <div class="input-group has-validation">
                <span class="input-group-text" id="inputGroupPrepend1"><i class="bi bi-envelope"></i></span>
                <input type="email" name="username" class="form-control" id="username" required="">
                <div class="invalid-feedback"><?= lang('System.login.fields.username-empty-error') ?></div>
            </div>
        </div>
        <div class="col-12">
            <button class="btn btn-primary w-100" type="submit"><?= lang('System.forgot-password.submit') ?></button>
        </div>
        <div class="col-12">
            <div class="btn-group btn-group-sm w-100">
                <a class="btn btn-outline-primary" href="<?= getenv('main_site') ?>"><i class="bi bi-chevron-left"></i> <?= lang('System.go-to-main-site') ?></a>
                <a class="btn btn-outline-primary" href="<?= base_url() ?>"><?= lang('System.pages.login') ?> <i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </form>
<?php $this->endSection() ?>