<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= ('new' == $mode ? lang('Business.user-management.new-user-info') : $user['user_name_first'] . ' ' . $user['user_name_last']) ?></h2>
                    <div class="col-12 col-lg-6">
                        <?php if ('edit' == $mode) : ?>
                            <div class="float-end avatar-4x"><?= retrieve_avatars($user['email_address'], $user['user_name_first'] . ' ' . $user['user_name_last']) ?></div>
                        <?php endif; ?>
                        <h3><?= lang('Business.user-management.generic-info') ?></h3>
                        <?php
                        $email_attr['type'] = 'email';
                        if ('edit' == $mode) {
                            $email_attr['readonly'] = 'true';
                        }
                        echo build_form_input('email_address', lang('UserMaster.field.email_address'), $email_attr, @$user['email_address']);
                        echo '<div class="row"><div class="col-6">';
                        echo build_form_input('user_name_first', lang('UserMaster.field.user_name_first'), [
                            'type' => 'text',
                        ], @$user['user_name_first']);
                        echo '</div><div class="col-6">';
                        echo build_form_input('user_name_last', lang('UserMaster.field.user_name_last'), [
                            'type' => 'text',
                        ], @$user['user_name_last']);
                        echo '</div></div>';
                        foreach ($all_languages as $lang_code => $language_name) {
                            echo build_form_input('user_public_local_names_' . $lang_code, lang('UserMaster.field.user_public_name') . ' (' . $language_name . ')', [
                                'type'             => 'text',
                                'data-explanation' => lang('UserMaster.explanation.user_public_name')
                            ], @$user['user_public_local_names'][$lang_code]);
                        }
                        $account_status_attr['type'] = 'select';
                        $account_status_options      = [
                            'A' => lang('UserMaster.enum.account_status.A'),
//                            'P' => lang('UserMaster.enum.account_status.P'),
                            'B' => lang('UserMaster.enum.account_status.B'),
                            'S' => lang('UserMaster.enum.account_status.S'),
                        ];
                        if ('edit' != $mode) {
                            $account_status_attr['readonly'] = 'true';
                            $user['account_status']          = 'P';
                            $account_status_options          = [
                                'P' => lang('UserMaster.enum.account_status.P')
                            ];
                        }
                        echo build_form_input('account_status', lang('UserMaster.field.account_status'), $account_status_attr, @$user['account_status'], '', $account_status_options);
                        ?>
                        <div class="text-end">
                            <button class="btn btn-primary" id="btn-save-master"><?= lang('System.buttons.save') ?></button>
                        </div>
                    </div>
                    <?php if ('edit' == $mode) : ?>
                        <div class="col-12 col-md-6">
                            <h3 class="mt-5 pt-5"><?= lang('Business.user-management.custom-attributes') ?></h3>
                            <h4 class="mt-3"><?= lang('Business.user-management.language-proficiency-level') ?></h4>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th><?= lang('BusinessUserLanguage.field.language_code') ?></th>
                                        <th><?= lang('BusinessUserLanguage.field.proficiency_level') ?></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (empty($user_languages_skills)) : ?>
                                        <tr>
                                            <td colspan="3"><?= lang('System.generic-term.no-data') ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($user_languages_skills as $lc => $pl) : ?>
                                        <tr>
                                            <td><?= $user_language_list[$lc] ?></td>
                                            <td><?= $proficiencies[$pl['proficiency_level']] ?></td>
                                            <td class="text-end"><button class="btn btn-outline-danger btn-sm btn-delete-user-language" id="btn-delete-user-language-<?= $lc ?>" data-bul-id="<?= $pl['id'] ?>" data-language-code="<?= $lc ?>"><?= lang('System.buttons.remove') ?></button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <h4 class="mt-3"><?= lang('Business.user-management.new-language') ?></h4>
                            <?php
                            echo '<div class="row"><div class="col-6">';
                            echo build_form_input('language_code', lang('BusinessUserLanguage.field.language_code'), [
                                'type' => 'select',
                            ], '', '', $user_language_list);
                            echo '</div><div class="col-6">';
                            echo build_form_input('proficiency_level', lang('BusinessUserLanguage.field.proficiency_level'), [
                                'type' => 'select',
                            ], '', '', $proficiencies);
                            echo '</div></div>';
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary btn-sm btn-new-user-language" id="btn-new-user-language"><?= lang('System.buttons.new') ?></button>
                            </div>
                            <?php if (!empty($all_attribute_fields)) : ?>
                                <h4 class="mt-3"><?= lang('Business.user-management.other-attributes') ?></h4>
                                <?php
                                $true_false_options = [
                                    'Y' => lang('System.generic-term.yes'),
                                    'N' => lang('System.generic-term.no'),
                                ];
                                foreach ($all_attribute_fields as $custom_field) {
                                    $field_name    = json_decode($custom_field['attribute_local_names'], true);
                                    $field_name    = $field_name[$lang] ?? $custom_field['attribute_name'];
                                    $bua_id        = $custom_field['id'];
                                    $field_id      = 'custom_field_' . $bua_id;
                                    $data_type     = $custom_field['data_type'];
                                    $current_value = $all_attribute_values[$bua_id]['value'] ?? '';
                                    $current_id    = $all_attribute_values[$bua_id]['id'] ?? 0;
                                    $lc_array      = [];
                                    unset($field_ids);
                                    echo '<div class="row mb-3"><div class="col-12">';
                                    if ('num' == $data_type) {
                                        $unit = json_decode($custom_field['data_unit'], true);
                                        $unit = $unit[$lang] ?? '';
                                        echo build_form_input($field_id, $field_name . ' (' . $unit . ')', [
                                            'type'          => 'number',
                                            'data-value-id' => $current_id,
                                        ], $current_value, 'custom-attribute');
                                    } else if ('translated_text' == $data_type) {
                                        foreach ($all_languages as $lang_code => $language_name) {
                                            echo build_form_input($field_id . '-' . $lang_code, $field_name . ' (' . $language_name . ')', [
                                                'type'            => 'text',
                                                'data-value-id'   => $current_id,
                                                'data-value-lang' => $lang_code,
                                            ], $current_value, 'custom-attribute');
                                            $lc_array[] = $lang_code;
                                        }
                                    } else if ('true-false' == $data_type) {
                                        echo build_form_input($field_id, $field_name, [
                                            'type'            => 'select',
                                            'data-value-id'   => $current_id,
                                        ], $current_value, 'custom-attribute', $true_false_options);
                                    } else if ('list' == $data_type) {
                                        $options_raw = json_decode($custom_field['data_list'], true);
                                        $options     = [];
                                        foreach ($options_raw as $key => $option) {
                                            $options[$key] = $option[$lang] ?? $option['en'];
                                        }
                                        echo build_form_input($field_id, $field_name, [
                                            'type'            => 'select',
                                            'data-value-id'   => $current_id,
                                        ], $current_value, 'custom-attribute', $options);
                                    }
                                    $lc_str = implode(',', $lc_array);
                                    echo '</div><div class="col-12 text-end">';
                                    echo '<button class="btn btn-primary btn-sm btn-save-custom-attribute" data-field-id="' . $field_id . '" data-value-id="' . $current_id . '" data-language-codes="' . $lc_str . '" data-bua-id="' . $bua_id . '" data-field-type="' . $data_type . '">' . lang('System.buttons.new') . '</button>';
                                    echo '</div></div>';
                                }
                                ?>
                            <?php endif; ?>
                            <h3 class="mt-5 pt-5"><?= lang('Business.user-management.link-to-business') ?></h3>
                            <?php
                            echo build_form_input('user_role', lang('BusinessUser.field.user_role'), [
                                'type' => 'select',
                            ], @$businessUser['user_role'], '', [
                                'OWNER'   => lang('BusinessUser.enum.user_role.OWNER'),
                                'MANAGER' => lang('BusinessUser.enum.user_role.MANAGER'),
                                'STAFF'   => lang('BusinessUser.enum.user_role.STAFF')
                            ]);
                            echo build_form_input('role_status', lang('BusinessUser.field.role_status'), [
                                'type' => 'select',
                            ], @$businessUser['role_status'], '', [
                                'ACTIVE'  => lang('BusinessUser.enum.role_status.ACTIVE'),
                                'REVOKED' => lang('BusinessUser.enum.role_status.REVOKED')
                            ]);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-business-user"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <h3 class="mt-5 pt-5"><?= lang('Business.user-management.link-to-branches') ?></h3>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th><?= lang('BranchUser.field.branch_id') ?></th>
                                        <th><?= lang('BranchUser.field.user_role') ?></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (empty($branchUser)) : ?>
                                    <tr>
                                        <td colspan="3"><?= lang('Business.user-management.no-branches') ?></td>
                                    </tr>
                                    <?php else : ?>
                                    <?php foreach ($branchUser as $row) : ?>
                                        <?php $branch_local_names = json_decode($row['branch_local_names'], true); ?>
                                        <?php unset($branches[$row['branch_id']]); ?>
                                        <tr>
                                            <td><?= $branch_local_names[$lang] ?? $row['branch_name'] ?></td>
                                            <td>
                                                <?php
                                                echo build_form_input('user_role-' . $row['id'], '', [
                                                    'type'    => 'select',
                                                    'data-id' => $row['id'],
                                                ], @$row['user_role'], 'form-control-sm', [
                                                    'STAFF'  => lang('BranchUser.enum.user_role.STAFF'),
                                                    'MANAGER' => lang('BranchUser.enum.user_role.MANAGER')
                                                ]);
                                                ?>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-primary btn-sm btn-update-branch-user" id="btn-update-branch-user-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>" data-target="user_role-<?= $row['id'] ?>"><?= lang('System.buttons.save') ?></button>
                                                <button class="btn btn-outline-danger btn-sm btn-delete-branch-user" id="btn-delete-branch-user-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>"><?= lang('System.buttons.remove') ?></button>
                                                <button class="btn btn-outline-danger btn-sm btn-delete-branch-user-confirm d-none" id="btn-delete-branch-user-<?= $row['id'] ?>-confirm" data-id="<?= $row['id'] ?>"><?= lang('System.buttons.remove-confirm') ?></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (!empty($branches)) : ?>
                                <h3><?= lang('Business.user-management.link-to-new-branch') ?></h3>
                                <?php
                                echo build_form_input('branch_id', lang('BranchUser.field.branch_id'), [
                                    'type' => 'select',
                                ], '', '', $branches);
                                echo build_form_input('branch_user_role', lang('BusinessUser.field.user_role'), [
                                    'type' => 'select',
                                ], '', '', [
                                    'STAFF'   => lang('BranchUser.enum.user_role.STAFF'),
                                    'MANAGER' => lang('BranchUser.enum.user_role.MANAGER')
                                ])
                                ?>
                                <div class="text-end">
                                    <button class="btn btn-primary" id="btn-save-branch-user"><?= lang('System.buttons.save') ?></button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <input type="hidden" name="id" id="id" value="<?= $user['id'] ?? 0 ?>" />
        <input type="hidden" name="business_user_id" id="business_user_id" value="<?= $businessUser['id'] ?? 0 ?>" />
        <input type="hidden" name="action" id="action" value="" />
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // main
            $('#btn-save-master').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['email_address', 'user_name_first', 'user_name_last', 'account_status'];
                gen_js_fields_checker($fields);
                foreach ($all_languages as $language_code => $language_name) {
                    $fields[] = 'user_public_local_names_' . $language_code;
                }
                ?>
                $('#btn-save-master').prop('disabled', true);
                $('#action').val('user_master');
                $.post(
                    "<?= base_url('admin/business/user-manage') ?>",
                    <?php $fields[] = 'id'; $fields[] = 'action'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save-master').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            let target_url = '<?= base_url('admin/business/user/') . ('edit' == $mode ? $userIdUrl : '') ?>';
                            setTimeout(function() { location.href=target_url; }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-save-master').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            $('#btn-save-business-user').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['user_role', 'role_status'];
                gen_js_fields_checker($fields);
                ?>
                $('#btn-save-business-user').prop('disabled', true);
                $('#action').val('business_user');
                $.post(
                    "<?= base_url('admin/business/user-manage') ?>",
                    <?php $fields[] = 'business_user_id'; $fields[] = 'action'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save-business-user').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-save-business-user').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            // branch
            $('#btn-save-branch-user').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['branch_user_role', 'branch_id'];
                gen_js_fields_checker($fields);
                ?>
                $('#btn-save-branch-user').prop('disabled', true);
                $('#action').val('branch_user_add');
                $.post(
                    "<?= base_url('admin/business/user-manage') ?>",
                    <?php $fields[] = 'id'; $fields[] = 'action'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save-branch-user').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-save-branch-user').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            $('.btn-update-branch-user').click(function (e) {
                e.preventDefault();
                let id = $(this).data('id'),
                    user_role_target = '#' + $(this).data('target'),
                    user_role = $(user_role_target).val();
                if ('' === user_role) {
                    $(user_role_target).focus();
                    return false;
                }
                $(this).prop('disabled', true);
                $.post(
                    "<?= base_url('admin/business/user-manage') ?>",
                    {
                        id: id,
                        user_role: user_role,
                        action: 'branch_user_update'
                    },
                    function (response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
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
            $('.btn-delete-branch-user').click(function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                $(this).addClass('d-none');
                $('#btn-delete-branch-user-'+id+'-confirm').removeClass('d-none');
            });
            $('.btn-delete-branch-user-confirm').click(function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                $.post(
                    "<?= base_url('admin/business/user-manage') ?>",
                    {
                        id: id,
                        action: 'branch_user_delete'
                    },
                    function (response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
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
            // language
            $('#btn-new-user-language').click(function (e) {
                e.preventDefault();
                let language_code = $('#language_code').val(),
                    proficiency_level = $('#proficiency_level').val();
                if ('' === language_code) {
                    $('#language_code').focus();
                    return false;
                } else if ('' === proficiency_level) {
                    $('#proficiency_level').focus();
                    return false;
                }
                $.post(
                    "<?= base_url('admin/business/user-manage') ?>",
                    {
                        business_user_id: $('#business_user_id').val(),
                        language_code: language_code,
                        proficiency_level: proficiency_level,
                        action: 'business_user_language_new'
                    },
                    function (response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
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
            $('.btn-delete-user-language').click(function (e) {
                e.preventDefault();
                let business_user_language_id = $(this).data('bul-id');
                $.post(
                    "<?= base_url('admin/business/user-manage') ?>",
                    {
                        business_user_language_id: business_user_language_id,
                        action: 'business_user_language_delete'
                    },
                    function (response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
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
            // attribute
            $('.btn-save-custom-attribute').click(function (e) {
                e.preventDefault();
                let id = $(this).data('field-id'),
                    attribute_value_id = $(this).data('value-id'),
                    language_codes = $(this).data('language-codes'),
                    attribute_value = '',
                    bua_id = $(this).data('bua-id'),
                    field_type = $(this).data('field-type');
                if ('translated_text' === field_type) {
                    let lc_array = language_codes.split(','),
                        attr = '';
                    lc_array.forEach(function(lc) {
                        attr += lc+'::'+$('#'+id+'-'+lc).val()+'//';
                        console.log('>>> ' + attr);
                    });
                    attribute_value = attr.slice(0, -2);
                } else {
                    attribute_value = $('#'+id).val();
                }
                console.log('ID ' + id);
                console.log('BUAV ID ' + attribute_value_id);
                console.log('LC ' + language_codes);
                console.log('VALUE ' + attribute_value);
                console.log('BUA ID ' + bua_id);
                console.log('field_type ' + field_type);
                $.post(
                    "<?= base_url('admin/business/user-manage') ?>",
                    {
                        business_user_attribute_value_id: attribute_value_id,
                        business_user_id: $('#business_user_id').val(),
                        business_user_attribute_id: bua_id,
                        attribute_value: attribute_value,
                        action: 'business_user_attribute_value'
                    },
                    function (response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            })
        });
    </script>
<?php $this->endSection() ?>