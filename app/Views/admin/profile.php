<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12 col-lg-10 col-xxl-8">
            <div class="card">
                <div class="card-body p-3">
                    <div class="float-end">
                        <?= $session->avatar ?>
                    </div>
                    <h5><i class="bi bi-lock"></i> <?= lang('Admin.profile.controlled-account-data') ?></h5>
                    <div class="row">
                        <div class="col-12"><p><small><?= lang('UserMaster.field.email_address') ?></small><br><?= $session->user['email_address'] ?></p></div>
                        <div class="col-6"><p><small><?= lang('UserMaster.field.user_name_first') ?></small><br><?= $session->user['user_name_first'] ?></p></div>
                        <div class="col-6"><p><small><?= lang('UserMaster.field.user_name_last') ?></small><br><?= $session->user['user_name_last'] ?></p></div>
                        <div class="col-6"><p><small><?= lang('UserMaster.field.password_expiry') ?></small><br><?= format_date($session->user['password_expiry']) ?></p></div>
                        <div class="col-6"><p><small><?= lang('UserMaster.field.account_status') ?></small><br><?= lang('UserMaster.enum.account_status.' . $session->user['account_status']) ?></p></div>
                    </div>
                    <hr class="my-3"/>
                    <!-- PROFILE DATA -->
                    <h5><i class="bi bi-person-badge"></i> <?= lang('Admin.profile.profile-data') ?></h5>
                    <?php
                    echo build_form_input('telephone_number', lang('UserMaster.field.telephone_number'), ['type' => 'tel'], $session->user['telephone_number']);
                    $lang_options = get_available_locales('long');
                    echo build_form_input('lang_code', lang('UserMaster.field.lang_code'), ['type' => 'select', 'required' => 'true'], $session->user['lang_code'], '', $lang_options);
                    echo build_form_input('user_gender', lang('UserMaster.field.user_gender'), ['type' => 'select'], $session->user['user_gender'], '', lang('UserMaster.enum.user_gender'));
                    echo build_form_input('user_date_of_birth', lang('UserMaster.field.user_date_of_birth'), ['type' => 'date'], $session->user['user_date_of_birth']);
                    $country_options = array_map(function ($names) {
                        return $names['common_name'];
                    }, get_country_codes()['countries']);
                    echo build_form_input('user_nationality', lang('UserMaster.field.user_nationality'), ['type' => 'select'], $session->user['user_nationality'], '', $country_options);
                    echo build_form_input('profile_status_msg', lang('UserMaster.field.profile_status_msg'), ['type' => 'text'], $session->user['profile_status_msg']);
                    ?>
                    <div class="text-end">
                        <button id="btn-save-changes" type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> <?= lang('System.buttons.save') ?></button>
                    </div>
                    <hr class="my-3"/>
                    <!-- UPLOAD AVATAR -->
                    <h5><i class="bi bi-cloud-arrow-up"></i> <?= lang('Admin.profile.upload-avatar') ?></h5>
                    <form id="form-upload-avatar" action="<?= base_url('/admin/profile') ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="script_action" value="upload-avatar"/>
                        <input type="file" id="avatar" name="avatar" class="form-control my-3"/>
                        <p class="small"><?= lang('Admin.profile.upload-explanation') ?></p>
                        <div class="text-end">
                            <button id="btn-upload-avatar" type="submit" class="btn btn-primary"><i class="bi bi-cloud-arrow-up"></i> <?= lang('System.buttons.upload') ?></button>
                            <button id="btn-remove-avatar" type="button" class="btn btn-outline-danger"><i class="bi bi-trash"></i> <?= lang('System.buttons.remove') ?></button>
                            <button id="btn-remove-avatar-confirm" type="button" class="btn btn-outline-danger" style="display:none"><i class="bi bi-exclamation-triangle"></i> <?= lang('System.buttons.remove-confirm') ?></button>
                        </div>
                    </form>
                    <hr class="my-3"/>
                    <!-- CHANGE PASSWORD -->
                    <h5><i class="bi bi-lock"></i> <?= lang('Admin.profile.change-password') ?></h5>
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
                            <i class="bi <?= (5 < $i ? 'password-strength-circle-invert bi-check-circle-fill text-success' : 'password-strength-circle bi-x-circle-fill text-danger') ?>" id="password-requirement-item-<?= $i ?>"></i> <?= lang('Admin.profile.new-password-requirement.item-' . $i) ?><br>
                        <?php endfor; ?>
                        <label><input type="hidden" id="password-strength-count" name="password-strength-count" value="0" /></label>
                    </p>
                    <div class="text-end">
                        <button id="btn-change-password" type="submit" class="btn btn-primary"><i class="bi bi-lock"></i>  <?= lang('Admin.profile.change-password') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#new_password')
                .on('keyup', function () {
                    // Reset
                    $('.password-strength-circle').removeClass('bi-check-circle-fill text-success').addClass('bi-x-circle-fill text-danger');
                    $('.password-strength-circle-invert').removeClass('bi-x-circle-fill text-danger').addClass('bi-check-circle-fill text-success');
                    // Check password
                    let password = $(this).val();
                    let strength = 0;
                    if (password.length >= 8) { // Password must be at least 8 characters long.
                        strength++;
                        $('#password-requirement-item-1').removeClass('bi-x-circle-fill text-danger').addClass('bi-check-circle-fill text-success');
                    }
                    if (password.match(/[0-9]/)) { // Password must contain at least one number.
                        strength++;
                        $('#password-requirement-item-2').removeClass('bi-x-circle-fill text-danger').addClass('bi-check-circle-fill text-success');
                    }
                    if (password.match(/[A-Z]/)) { // Password must contain at least one uppercase letter.
                        strength++;
                        $('#password-requirement-item-3').removeClass('bi-x-circle-fill text-danger').addClass('bi-check-circle-fill text-success');
                    }
                    if (password.match(/[a-z]/)) { // Password must contain at least one lowercase letter.
                        strength++;
                        $('#password-requirement-item-4').removeClass('bi-x-circle-fill text-danger').addClass('bi-check-circle-fill text-success');
                    }
                    if (password.match(/[@$!%*?&]/)) { // Password must contain at least one special character: @$!%*?&
                        strength++;
                        $('#password-requirement-item-5').removeClass('bi-x-circle-fill text-danger').addClass('bi-check-circle-fill text-success');
                    }
                    let regex_name = new RegExp('^(?:(?!<?= strtolower($session->user['user_name_first']) ?>|<?= strtolower($session->user['user_name_last']) ?>).)*$', 'i');
                    if (password.match(regex_name)) { // Password must not contain first and/or family name(s).
                        strength++;
                        $('#password-requirement-item-6').removeClass('bi-x-circle-fill text-danger').addClass('bi-check-circle-fill text-success');
                    } else {
                        $('#password-requirement-item-6').removeClass('bi-check-circle-fill text-success').addClass('bi-x-circle-fill text-danger');
                    }
                    let regex_common = new RegExp('^(?:(?!<?= BANNED_PASSWORD ?>).)*$', 'i');
                    if (password.match(regex_common)) { // Password must not be too common.
                        strength++;
                        $('#password-requirement-item-7').removeClass('bi-x-circle-fill text-danger').addClass('bi-check-circle-fill text-success');
                    } else {
                        $('#password-requirement-item-7').removeClass('bi-check-circle-fill text-success').addClass('bi-x-circle-fill text-danger');
                    }
                    let regex_illegal = new RegExp('^[0-9A-Za-z@$!%*?&]*$', 'i');
                    if (password.match(regex_illegal)) { // Password must not contain illegal letters (letters apart from number, uppercase and lowercase Latin characters, and @$!%*?&).
                        strength++;
                        $('#password-requirement-item-8').removeClass('bi-x-circle-fill text-danger').addClass('bi-check-circle-fill text-success');
                    } else {
                        $('#password-requirement-item-8').removeClass('bi-check-circle-fill text-success').addClass('bi-x-circle-fill text-danger');
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
            //$('#btn-save-changes').on('click', function (e) {
            //    e.preventDefault();
            //    let field_ids = ['telephone_country_calling_code_select', 'telephone_number', 'user_gender', 'user_date_of_birth', 'preferred_language'];
            //    for (let i = 0; i < field_ids.length; i++) {
            //        if ($('#' + field_ids[i]).val() === '') {
            //            toastr.warning('<?php //= lang('System.response-msg.error.please-check-empty-field') ?>//');
            //            $('#' + field_ids[i]).focus();
            //            return;
            //        }
            //    }
            //    $('#btn-save-changes').prop('disabled', true);
            //    $.ajax({
            //        url: '<?php //= base_url('/admin/profile') ?>//',
            //        type: 'POST',
            //        data: {
            //            script_action: 'save-info',
            //            telephone_country_calling_code: $('#telephone_country_calling_code_select').val(),
            //            telephone_number: $('#telephone_number').val(),
            //            user_gender: $('#user_gender').val(),
            //            user_date_of_birth: $('#user_date_of_birth').val(),
            //            user_profile_status: $('#user_profile_status').val(),
            //            preferred_language: $('#preferred_language').val(),
            //            user_nationality: $('#user_nationality').val()
            //        },
            //        success: function (response) {
            //            $('#btn-save-changes').prop('disabled', false);
            //            if (response.success) {
            //                toastr.success(response.toast);
            //                setTimeout(function () {
            //                    window.location.href = response.redirect;
            //                }, 5000);
            //            } else {
            //                toastr.error(response.toast);
            //            }
            //        },
            //        error: function (xhr, status, error) {
            //            $('#btn-save-changes').prop('disabled', false);
            //            let response = JSON.parse(xhr.responseText);
            //            let error_message = (response.toast ?? '<?php //= lang('System.response-msg.error.generic-error') ?>//');
            //            toastr.error(error_message);
            //        }
            //    });
            //});
            //$('#btn-upload-avatar').on('click', function (e) {
            //    e.preventDefault();
            //    // check if the file is selected
            //    if ($('#avatar').val() === '') {
            //        toastr.warning('<?php //= lang('System.response-msg.error.please-check-empty-field') ?>//');
            //        $('#avatar').focus();
            //        return;
            //    }
            //    $('#btn-upload-avatar').prop('disabled', true);
            //    // submit #form-upload-avatar form in AJAX
            //    $.ajax({
            //        url: '<?php //= base_url('/admin/profile') ?>//',
            //        type: 'POST',
            //        data: new FormData($('#form-upload-avatar')[0]),
            //        contentType: false,
            //        cache: false,
            //        processData: false,
            //        success: function (response) {
            //            $('#btn-upload-avatar').prop('disabled', false);
            //            if (response.success) {
            //                toastr.success(response.toast);
            //                setTimeout(function () {
            //                    window.location = '<?php //= base_url('/admin/profile') ?>//';
            //                }, 5000);
            //            } else {
            //                toastr.error(response.toast);
            //            }
            //        },
            //        error: function (xhr, status, error) {
            //            let response = JSON.parse(xhr.responseText);
            //            let error_message = (response.toast ?? '<?php //= lang('System.response-msg.error.generic-error') ?>//');
            //            $('#btn-upload-avatar').prop('disabled', false);
            //            toastr.error(error_message);
            //        }
            //    });
            //});
            //$('#btn-remove-avatar').on('click', function (e) {
            //    e.preventDefault();
            //    $('#btn-remove-avatar').hide();
            //    $('#btn-remove-avatar-confirm').show();
            //});
            //$('#btn-remove-avatar-confirm').on('click', function (e) {
            //    e.preventDefault();
            //    $('#btn-remove-avatar-confirm').prop('disabled', true);
            //    $.ajax({
            //        url: '<?php //= base_url('/admin/profile') ?>//',
            //        type: 'POST',
            //        data: {
            //            script_action: 'remove-avatar'
            //        },
            //        success: function (response) {
            //            $('#btn-remove-avatar-confirm').prop('disabled', false);
            //            if (response.success) {
            //                toastr.success(response.toast);
            //                setTimeout(function () {
            //                    window.location = '<?php //= base_url('/admin/profile') ?>//';
            //                }, 5000);
            //            } else {
            //                toastr.error(response.toast);
            //            }
            //        },
            //        error: function (xhr, status, error) {
            //            let response = JSON.parse(xhr.responseText);
            //            let error_message = (response.toast ?? '<?php //= lang('System.response-msg.error.generic-error') ?>//');
            //            $('#btn-remove-avatar-confirm').prop('disabled', false);
            //            toastr.error(error_message);
            //        }
            //    });
            //});
            //$('#btn-change-password').on('click', function (e) {
            //    e.preventDefault();
            //    let field_ids = ['current_password', 'new_password', 'confirm_password'];
            //    for (let i = 0; i < field_ids.length; i++) {
            //        if ($('#' + field_ids[i]).val() === '') {
            //            toastr.warning('<?php //= lang('System.response-msg.error.please-check-empty-field') ?>//');
            //            $('#' + field_ids[i]).focus();
            //            return;
            //        }
            //    }
            //    if ($('#new_password').val() !== $('#confirm_password').val()) {
            //        toastr.warning('<?php //= lang('System.response-msg.error.password-does-not-matched') ?>//');
            //        $('#confirm_password').val('').focus();
            //        return;
            //    }
            //    $('#btn-change-password').prop('disabled', true);
            //    $.ajax({
            //        url: '<?php //= base_url('/admin/profile') ?>//',
            //        type: 'POST',
            //        data: {
            //            script_action: 'change-password',
            //            current_password: $('#current_password').val(),
            //            new_password: $('#new_password').val(),
            //            confirm_password: $('#confirm_password').val()
            //        },
            //        success: function (response) {
            //            $('#btn-change-password').prop('disabled', false);
            //            if (response.success) {
            //                toastr.success(response.toast);
            //                $('#current_password').val('');
            //                $('#new_password').val('');
            //                $('#confirm_password').val('');
            //            } else {
            //                toastr.error(response.toast);
            //            }
            //        },
            //        error: function (xhr, status, error) {
            //            $('#btn-change-password').prop('disabled', false);
            //            let response = JSON.parse(xhr.responseText);
            //            let error_message = (response.toast ?? '<?php //= lang('System.response-msg.error.generic-error') ?>//');
            //            toastr.error(error_message);
            //        }
            //    });
            //});
        });
    </script>
<?php $this->endSection() ?>