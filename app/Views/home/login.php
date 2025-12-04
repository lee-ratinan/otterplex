<?php $this->extend('home/_layout'); ?>
<?= $this->section('content') ?>
    <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4"><?= lang('System.login.title') ?></h5>
        <p class="text-center small"><?= lang('System.login.instruction') ?></p>
    </div>
    <form class="row g-3 needs-validation" novalidate="">
        <div id="login-section">
            <div class="col-12 mb-3">
                <label for="username" class="form-label"><?= lang('System.login.fields.username') ?></label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend1"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="username" class="form-control" id="username" required="">
                    <div class="invalid-feedback"><?= lang('System.login.fields.username-empty-error') ?></div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <label for="password" class="form-label"><?= lang('System.login.fields.password') ?></label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend2"><i class="bi bi-asterisk"></i></span>
                    <input type="password" name="password" class="form-control" id="password" required="">
                    <div class="invalid-feedback"><?= lang('System.login.fields.password-empty-error') ?></div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <button id="btn-login" class="btn btn-primary w-100" type="submit"><?= lang('System.login.title') ?></button>
            </div>
        </div>
        <div id="otp-section" class="d-none">
            <div class="col-12 mb-3">
                <label for="otp" class="form-label"><?= lang('System.login.fields.otp') ?></label>
                <div class="input-group">
                    <span class="input-group-text" id="inputGroupPrepend1"><i class="bi bi-asterisk"></i></span>
                    <input type="number" name="otp" class="form-control" id="otp" min="0" max="999999" required="">
                </div>
            </div>
            <div class="col-12 mb-3">
                <button id="btn-otp" class="btn btn-primary w-100" type="submit"><?= lang('System.login.btn-otp') ?></button>
            </div>
        </div>
        <div class="col-12">
            <div class="btn-group btn-group-sm w-100">
                <a class="btn btn-outline-primary" href="<?= getenv('main_site') ?>"><i class="bi bi-chevron-left"></i> <?= lang('System.go-to-main-site') ?></a>
                <a class="btn btn-outline-danger" href="<?= base_url('forgot-password') ?>"><i class="bi bi-exclamation-circle"></i> <?= lang('System.pages.forgot-password') ?></a>
            </div>
            <p class="small mt-3"><?= lang('System.login.dont-have-account') ?> <a href="<?= base_url('create-account') ?>"><?= lang('System.pages.create-account') ?></a></p>
        </div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('#btn-login').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['username', 'password'];
                gen_js_fields_checker($fields);
                ?>
                $.post(
                    "<?= base_url('login') ?>",
                    <?php gen_json_fields_to_fields($fields) ?>,
                    function(response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            // Future: add OTP feature when ready
                            window.location.href = '<?= base_url('dashboard') ?>';
                        } else {
                            toastr.error(response.message)
                        }
                    },
                    "json"
                ).fail(function () {
                    toastr.error('<?= lang('System.response-msg.error.generic') ?>')
                });
            })
        });
    </script>
<?php $this->endSection() ?>
