<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col col-lg-6">
                            <div class="float-end avatar-4x">
                                <?= $session->avatar ?>
                            </div>
                            <h2><i class="fa-solid fa-lock"></i> <?= lang('Admin.profile.controlled-account-data') ?></h2>
                            <div class="row">
                                <div class="col-12"><p><small><?= lang('UserMaster.field.email_address') ?></small><br><?= $session->user['email_address'] ?></p></div>
                                <div class="col-6"><p><small><?= lang('UserMaster.field.user_name_first') ?></small><br><?= $session->user['user_name_first'] ?></p></div>
                                <div class="col-6"><p><small><?= lang('UserMaster.field.user_name_last') ?></small><br><?= $session->user['user_name_last'] ?></p></div>
                                <div class="col-6"><p><small><?= lang('UserMaster.field.password_expiry') ?></small><br><?= format_date($session->user['password_expiry']) ?></p></div>
                                <div class="col-6"><p><small><?= lang('UserMaster.field.account_status') ?></small><br><?= lang('UserMaster.enum.account_status.' . $session->user['account_status']) ?></p></div>
                            </div>
                            <hr class="my-3"/>
                            <!-- PROFILE DATA -->
                            <h2><i class="fa-solid fa-id-badge"></i> <?= lang('Admin.profile.profile-data') ?></h2>
                            <?php
                            echo build_form_input('telephone_number', lang('UserMaster.field.telephone_number'), ['type' => 'tel'], $session->user['telephone_number']);
                            $lang_options = get_available_locales('long');
                            echo build_form_input('lang_code', lang('UserMaster.field.lang_code'), ['type' => 'select', 'required' => 'true'], $session->user['lang_code'], '', $lang_options);
                            echo build_form_input('user_gender', lang('UserMaster.field.user_gender'), ['type' => 'select'], $session->user['user_gender'], '', lang('UserMaster.enum.user_gender'));
                            echo build_form_input('user_date_of_birth', lang('UserMaster.field.user_date_of_birth'), ['type' => 'date'], $session->user['user_date_of_birth']);
                            $country_options = get_country_list();
                            echo build_form_input('user_nationality', lang('UserMaster.field.user_nationality'), ['type' => 'select'], $session->user['user_nationality'], '', $country_options);
                            echo build_form_input('profile_status_msg', lang('UserMaster.field.profile_status_msg'), ['type' => 'text'], $session->user['profile_status_msg']);
                            ?>
                            <div class="text-end">
                                <button id="btn-save-changes" type="submit" class="btn btn-primary"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <hr class="my-3"/>
                            <!-- UPLOAD AVATAR -->
                            <h2><i class="fa-solid fa-cloud-arrow-up"></i> <?= lang('Admin.profile.upload-avatar') ?></h2>
                            <form id="form-upload-avatar" action="<?= base_url('/admin/profile') ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="script_action" value="upload_avatar"/>
                                <input type="file" id="avatar" name="avatar" class="form-control my-3"/>
                                <p class="small"><?= lang('Admin.profile.upload-explanation') ?></p>
                                <div class="text-end">
                                    <button id="btn-upload-avatar" type="submit" class="btn btn-primary"><?= lang('System.buttons.upload') ?></button>
                                    <button id="btn-remove-avatar" type="button" class="btn btn-outline-danger"><?= lang('System.buttons.remove') ?></button>
                                    <button id="btn-remove-avatar-confirm" type="button" class="btn btn-outline-danger" style="display:none"><?= lang('System.buttons.remove-confirm') ?></button>
                                </div>
                            </form>
                            <hr class="my-3"/>
                            <!-- CHANGE PASSWORD -->
                            <h2><i class="fa-solid fa-lock"></i> <?= lang('Admin.profile.change-password') ?></h2>
                            <form>
                                <?php
                                echo build_form_input('current_password', lang('UserMaster.field.current_password'), ['type' => 'password', 'required'  => 'true', 'minlength' => '8', 'maxlength' => '32', 'autocomplete' => 'off']);
                                echo build_form_input('new_password', lang('UserMaster.field.new_password'), ['type' => 'password', 'required'  => 'true', 'minlength' => '8', 'maxlength' => '32', 'autocomplete' => 'off']);
                                echo build_form_input('confirm_password', lang('UserMaster.field.confirm_password'), ['type' => 'password', 'required'  => 'true', 'minlength' => '8', 'maxlength' => '32', 'autocomplete' => 'off']);
                                ?>
                            </form>
                            <p class="small">
                                <?= lang('Admin.profile.new-password-requirement.title') ?><br>
                                <?php for ($i = 1; $i <= 8; $i++) : ?>
                                    <i class="fa-solid <?= (5 < $i ? 'password-strength-circle-invert fa-circle-check text-success' : 'password-strength-circle fa-circle-xmark text-danger') ?>" id="password-requirement-item-<?= $i ?>"></i> <?= lang('Admin.profile.new-password-requirement.item-' . $i) ?><br>
                                <?php endfor; ?>
                                <label><input type="hidden" id="password-strength-count" name="password-strength-count" value="0" /></label>
                            </p>
                            <div class="text-end">
                                <button id="btn-change-password" type="submit" class="btn btn-primary"><i class="fa-solid fa-lock"></i>  <?= lang('Admin.profile.change-password') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <label for="script_action"><input type="hidden" name="script_action" id="script_action" value="" /></label>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FIX INPUTS
            $('#new_password')
                .on('keyup', function () {
                    // Reset
                    $('.password-strength-circle').removeClass('fa-circle-check text-success').addClass('fa-circle-xmark text-danger');
                    $('.password-strength-circle-invert').removeClass('fa-circle-xmark text-danger').addClass('fa-circle-check text-success');
                    // Check password
                    let password = $(this).val();
                    let strength = 0;
                    if (password.length >= 8) { // Password must be at least 8 characters long.
                        strength++;
                        $('#password-requirement-item-1').removeClass('fa-circle-xmark text-danger').addClass('fa-circle-check text-success');
                    }
                    if (password.match(/[0-9]/)) { // Password must contain at least one number.
                        strength++;
                        $('#password-requirement-item-2').removeClass('fa-circle-xmark text-danger').addClass('fa-circle-check text-success');
                    }
                    if (password.match(/[A-Z]/)) { // Password must contain at least one uppercase letter.
                        strength++;
                        $('#password-requirement-item-3').removeClass('fa-circle-xmark text-danger').addClass('fa-circle-check text-success');
                    }
                    if (password.match(/[a-z]/)) { // Password must contain at least one lowercase letter.
                        strength++;
                        $('#password-requirement-item-4').removeClass('fa-circle-xmark text-danger').addClass('fa-circle-check text-success');
                    }
                    if (password.match(/[@$!%*?&]/)) { // Password must contain at least one special character: @$!%*?&
                        strength++;
                        $('#password-requirement-item-5').removeClass('fa-circle-xmark text-danger').addClass('fa-circle-check text-success');
                    }
                    let regex_name = new RegExp('^(?:(?!<?= strtolower($session->user['user_name_first']) ?>|<?= strtolower($session->user['user_name_last']) ?>).)*$', 'i');
                    if (password.match(regex_name)) { // Password must not contain first and/or family name(s).
                        strength++;
                        $('#password-requirement-item-6').removeClass('fa-circle-xmark text-danger').addClass('fa-circle-check text-success');
                    } else {
                        $('#password-requirement-item-6').removeClass('fa-circle-check text-success').addClass('fa-circle-xmark text-danger');
                    }
                    let regex_common = new RegExp('^(?:(?!<?= BANNED_PASSWORD ?>).)*$', 'i');
                    if (password.match(regex_common)) { // Password must not be too common.
                        strength++;
                        $('#password-requirement-item-7').removeClass('fa-circle-xmark text-danger').addClass('fa-circle-check text-success');
                    } else {
                        $('#password-requirement-item-7').removeClass('fa-circle-check text-success').addClass('fa-circle-xmark text-danger');
                    }
                    let regex_illegal = new RegExp('^[0-9A-Za-z@$!%*?&]*$', 'i');
                    if (password.match(regex_illegal)) { // Password must not contain illegal letters (letters apart from number, uppercase and lowercase Latin characters, and @$!%*?&).
                        strength++;
                        $('#password-requirement-item-8').removeClass('fa-circle-xmark text-danger').addClass('fa-circle-check text-success');
                    } else {
                        $('#password-requirement-item-8').removeClass('fa-circle-check text-success').addClass('fa-circle-xmark text-danger');
                    }
                    $('#password-strength-count').val(strength);
                })
                .on('change', function () {
                        if (8 > parseInt($('#password-strength-count').val())) {
                            $(this).focus();
                            toastr.warning('<?= lang('Admin.profile.new-password-requirement.requirement-not-met') ?>');
                        }
                    }
                );
            $('#telephone_number').on('change', function () {
                let phone_number = $(this).val(),
                    country_code = '<?= $session->business['country_code'] ?>';
                $.post(
                    "<?= base_url('helper/format-phone-number') ?>",
                    {phone_number: phone_number, country_code: country_code},
                    function (response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            $('#telephone_number').val(response.e164);
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
            // SAVE
            $('#btn-save-changes').on('click', function (e) {
                e.preventDefault();
                <?php gen_js_fields_checker(['lang_code']); ?>
                $('#btn-save-changes').prop('disabled', true);
                $('#script_action').val('save_profile');
                $.post(
                    "<?= base_url('/admin/profile') ?>",
                    <?php gen_json_fields_to_fields(['script_action', 'telephone_number', 'lang_code', 'user_gender', 'user_date_of_birth', 'user_nationality', 'profile_status_msg']) ?>,
                    function (response, status) {
                        $('#btn-save-changes').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-save-changes').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            $('#btn-upload-avatar').on('click', function (e) {
                e.preventDefault();
                // check if the file is selected
                if ($('#avatar').val() === '') {
                    toastr.warning('<?= lang('System.response-msg.error.please-check-empty-field') ?>');
                    $('#avatar').focus();
                    return;
                }
                $('#btn-upload-avatar').prop('disabled', true);
                // submit #form-upload-avatar form in AJAX
                $.ajax({
                    url: '<?= base_url('/admin/profile') ?>',
                    type: 'POST',
                    data: new FormData($('#form-upload-avatar')[0]),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        $('#btn-upload-avatar').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#btn-upload-avatar').prop('disabled', false);
                        let response = JSON.parse(xhr.responseText);
                        let message = response.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                        toastr.error(message);
                    }
                });
            });
            $('#btn-remove-avatar').on('click', function (e) {
               e.preventDefault();
               $('#btn-remove-avatar').hide();
               $('#btn-remove-avatar-confirm').show();
            });
            $('#btn-remove-avatar-confirm').on('click', function (e) {
                e.preventDefault();
                $('#btn-remove-avatar-confirm').prop('disabled', true);
                $.ajax({
                    url: '<?= base_url('/admin/profile') ?>',
                    type: 'POST',
                    data: {
                        script_action: 'remove_avatar'
                    },
                    success: function (response) {
                        $('#btn-remove-avatar-confirm').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#btn-remove-avatar-confirm').prop('disabled', false);
                        let response = JSON.parse(xhr.responseText);
                        let message = response.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                        toastr.error(message);
                    }
                });
            });
            $('#btn-change-password').on('click', function (e) {
                e.preventDefault();
                $('#script_action').val('change_password');
                <?php
                $fields = ['current_password', 'new_password', 'confirm_password'];
                gen_js_fields_checker($fields);
                ?>
                if ($('#new_password').val() !== $('#confirm_password').val()) {
                    toastr.warning('<?= lang('System.response-msg.error.password-does-not-matched') ?>');
                    $('#confirm_password').val('').focus();
                    return;
                }
                $('#btn-change-password').prop('disabled', true);
                <?php $fields = array_merge(['script_action'], $fields); ?>
                $.post(
                    "<?= base_url('/admin/profile') ?>",
                    <?php gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-change-password').prop('disabled', false);
                        $('#current_password').val('');
                        $('#new_password').val('');
                        $('#confirm_password').val('');
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-change-password').prop('disabled', false);
                    $('#current_password').val('');
                    $('#new_password').val('');
                    $('#confirm_password').val('');
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
        });
    </script>
<?php $this->endSection() ?>