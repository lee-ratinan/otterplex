<?php $this->extend('home/_layout'); ?>
<?= $this->section('content') ?>
    <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4"><?= lang('System.pages.reset-password') ?></h5>
        <p class="text-center small"><?= lang('System.reset-password.instruction') ?></p>
    </div>
    <?php if ($token_validity) : ?>
    <form class="row g-3 needs-validation" novalidate="">
        <div class="col-12">
            <label for="password" class="form-label"><?= lang('System.reset-password.fields.password') ?></label>
            <div class="input-group has-validation">
                <span class="input-group-text" id="inputGroupPrepend1"><i class="fa-solid fa-asterisk"></i></span>
                <input type="password" name="password" class="form-control" id="password" required="">
                <div class="invalid-feedback"><?= lang('System.reset-password.fields.password-empty-error') ?></div>
            </div>
        </div>
        <div class="col-12">
            <label for="password-confirm" class="form-label"><?= lang('System.reset-password.fields.password-confirm') ?></label>
            <div class="input-group has-validation">
                <span class="input-group-text" id="inputGroupPrepend2"><i class="fa-solid fa-asterisk"></i></span>
                <input type="password" name="password-confirm" class="form-control" id="password-confirm" required="">
                <div class="invalid-feedback"><?= lang('System.reset-password.fields.password-confirm-empty-error') ?></div>
            </div>
        </div>
        <div class="col-12">
            <button class="btn btn-primary w-100" type="submit"><?= lang('System.reset-password.change-password') ?></button>
        </div>
    </form>
    <?php else : ?>
    <p><?= lang('System.reset-password.token-invalid') ?></p>
    <?php endif; ?>
    <div class="col-12 mt-3">
        <div class="btn-group btn-group-sm w-100">
            <a class="btn btn-outline-primary" href="<?= getenv('main_site') ?>"><i class="fa-solid fa-chevron-left"></i> <?= lang('System.go-to-main-site') ?></a>
            <a class="btn btn-outline-primary" href="<?= base_url() ?>"><?= lang('System.pages.login') ?> <i class="fa-solid fa-chevron-right"></i></a>
        </div>
    </div>
<?php $this->endSection() ?>