<?php $this->extend('home/_layout'); ?>
<?= $this->section('content') ?>
    <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4"><?= lang('System.pages.create-account') ?></h5>
        <p class="text-center small"><?= lang('System.create-account.instruction') ?></p>
    </div>
    <form class="row g-3 needs-validation" novalidate="">
        <div class="col-12">
            <label for="first-name" class="form-label"><?= lang('System.create-account.fields.full-name') ?></label>
            <label for="last-name" class="d-none"><?= lang('System.create-account.fields.last-name') ?></label>
            <div class="input-group has-validation">
                <input type="text" name="first-name" class="form-control" id="first-name" required="" placeholder="<?= lang('System.create-account.fields.first-name') ?>">
                <input type="text" name="last-name" class="form-control" id="last-name" required="" placeholder="<?= lang('System.create-account.fields.last-name') ?>">
                <div class="invalid-feedback"><?= lang('System.create-account.fields.full-name-empty-error') ?></div>
            </div>
        </div>
        <div class="col-12">
            <label for="username" class="form-label"><?= lang('System.create-account.fields.username') ?></label>
            <div class="input-group has-validation">
                <span class="input-group-text" id="inputGroupPrepend1"><i class="bi bi-envelope"></i></span>
                <input type="email" name="username" class="form-control" id="username" required="">
                <div class="invalid-feedback"><?= lang('System.create-account.fields.username-empty-error') ?></div>
            </div>
        </div>
        <div class="col-12">
            <label for="password" class="form-label"><?= lang('System.create-account.fields.password') ?></label>
            <div class="input-group has-validation">
                <span class="input-group-text" id="inputGroupPrepend2"><i class="bi bi-asterisk"></i></span>
                <input type="password" name="password" class="form-control" id="password" required="">
                <div class="invalid-feedback"><?= lang('System.create-account.fields.password-empty-error') ?></div>
            </div>
        </div>
        <div class="col-12">
            <label for="plan" class="form-label"><?= lang('System.create-account.fields.plan') ?></label>
            <select class="form-control" id="plan" name="plan">
                <?php foreach (lang('System.create-account.fields.plan-options') as $key => $value) : ?>
                    <option value="<?= $key ?>"><?= $value ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback"><?= lang('System.create-account.fields.plan-empty-error') ?></div>
        </div>
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required="">
                <label class="form-check-label" for="acceptTerms"><?= lang('System.create-account.fields.i-agree') ?> <a href="<?= getenv('main_site') ?>terms-and-conditions"><?= lang('System.create-account.fields.terms') ?></a></label>
                <div class="invalid-feedback"><?= lang('System.create-account.fields.terms-error') ?></div>
            </div>
        </div>
        <div class="col-12">
            <button class="btn btn-primary w-100" type="submit"><?= lang('System.pages.create-account') ?></button>
        </div>
        <div class="col-12">
            <p class="small"><?= lang('System.create-account.already-have-account') ?> <a href="<?= base_url() ?>"><?= lang('System.pages.login') ?></a></p>
        </div>
    </form>
<?php $this->endSection() ?>
