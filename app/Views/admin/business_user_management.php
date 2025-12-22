<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= ('new' == $mode ? lang('Business.user-management.new-user-info') : $user['user_name_first'] . ' ' . $user['user_name_last']) ?></h2>
                    <div class="col-12 col-lg-6">
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
                        $account_status_attr['type'] = 'select';
                        $account_status_options      = [
                            'A' => lang('UserMaster.enum.account_status.A'),
                            'P' => lang('UserMaster.enum.account_status.P'),
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
                            <h3><?= lang('Business.user-management.link-to-business') ?></h3>
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
                            <h3><?= lang('Business.user-management.link-to-branches') ?></h3>
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
                                    <?php foreach ($branchUser as $row) : ?>
                                        <?php $branch_local_names = json_decode($row['branch_local_names'], true); ?>
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
                                            <td>
                                                <button class="btn btn-primary btn-sm float-end"><?= lang('System.buttons.save') ?></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?= $branchCount ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <input type="hidden" name="id" id="id" value="<?= $user['id'] ?? 0 ?>" />
        <input type="hidden" name="action" id="action" value="" />
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('#btn-save-master').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['email_address', 'user_name_first', 'user_name_last', 'account_status'];
                gen_js_fields_checker($fields);
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
                            let user_id = '';
                            if (response.id) {
                                user_id = response.id * <?= ID_MASKED_PRIME ?>;
                            }
                            setTimeout(function() { location.href='<?= base_url('admin/business/user/') ?>' + user_id; }, 3000);
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
        });
    </script>
<?php $this->endSection() ?>