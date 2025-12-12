<?php $this->extend('home/_layout'); ?>
<?= $this->section('content') ?>
    <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4"><?= lang('System.pages.create-account') ?></h5>
        <p class="text-center small"><?= lang('System.create-account.instruction') ?></p>
    </div>
    <form class="row g-3 needs-validation" novalidate="">
        <div class="col-12">
            <label for="user_name_first" class="form-label"><?= lang('System.create-account.fields.full-name') ?></label>
            <label for="user_name_last" class="d-none"><?= lang('System.create-account.fields.last-name') ?></label>
            <div class="input-group">
                <input type="text" name="user_name_first" class="form-control" id="user_name_first" required="" placeholder="<?= lang('System.create-account.fields.first-name') ?>">
                <input type="text" name="user_name_last" class="form-control" id="user_name_last" required="" placeholder="<?= lang('System.create-account.fields.last-name') ?>">
            </div>
        </div>
        <div class="col-12">
            <label for="email_address" class="form-label"><?= lang('System.create-account.fields.username') ?></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email_address" class="form-control" id="email_address" required="">
            </div>
        </div>
        <div class="col-12">
            <label for="password" class="form-label"><?= lang('System.create-account.fields.password') ?></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-asterisk"></i></span>
                <input type="password" name="password" class="form-control" id="password" required="">
            </div>
        </div>
        <div class="col-12">
            <label for="confirm_password" class="form-label"><?= lang('System.create-account.fields.confirm-password') ?></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-asterisk"></i></span>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" required="">
            </div>
        </div>
        <div class="col-12">
            <label for="business_name" class="form-label"><?= lang('System.create-account.fields.business-name') ?></label>
            <input type="text" name="business_name" class="form-control" id="business_name" required="">
        </div>
        <div class="col-12">
            <label for="country_code" class="form-label"><?= lang('System.create-account.fields.country-code') ?></label>
            <select class="form-control" id="country_code" name="country_code">
                <?php foreach (get_available_countries() as $country_code => $country_name) : ?>
                    <option value="<?= $country_code ?>"><?= $country_name ?></option>
                <?php endforeach; ?>
            </select>
            <p class="small mt-1 ms-2"><?= lang('System.create-account.fields.country-code-note') ?></p>
        </div>
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required="">
                <label class="form-check-label" for="acceptTerms"><?= lang('System.create-account.fields.i-agree') ?> <a href="<?= getenv('main_site') ?>terms-and-conditions"><?= lang('System.create-account.fields.terms') ?></a></label>
            </div>
        </div>
        <div class="col-12">
            <button class="btn btn-primary w-100" id="btn-create-account" type="submit"><?= lang('System.pages.create-account') ?></button>
        </div>
        <div class="col-12">
            <p class="small"><?= lang('System.create-account.already-have-account') ?> <a href="<?= base_url() ?>"><?= lang('System.pages.login') ?></a></p>
        </div>
    </form>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        $('#btn-create-account').click(function (e) {
            e.preventDefault();
            <?php
            $fields = ['user_name_first', 'user_name_last', 'email_address', 'password', 'confirm_password', 'business_name', 'country_code'];
            gen_js_fields_checker($fields);
            ?>
            if (!$('#acceptTerms').prop('checked')) {
                $('#acceptTerms').focus();
                return false;
            }
            if (password !== confirm_password) {
                $('#confirm_password').focus();
                return false;
            }
            $.post(
                "<?= base_url('create-account') ?>",
                <?php gen_json_fields_to_fields($fields) ?>,
                function(response, status) {
                    if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                        toastr.success(response.message);
                        setTimeout(function() { window.location.href = '<?= base_url('login') ?>'; }, 3000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                "json"
            ).fail(function (response) {
                let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                toastr.error(message);
            });
        });
    });
</script>
<?php $this->endSection() ?>
