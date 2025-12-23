<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= $branch['branch_local_names'][$lang] ?? lang('Business.branch-management.generic-title') ?></h2>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h3><?= lang('Business.branch-management.generic-information') ?></h3>
                            <?php
                            echo build_form_input('subdivision_code', lang('BranchMaster.field.subdivision_code'), [
                                'type' => 'select',
                            ], @$branch['subdivision_code'], '', $subdivisions);
                            echo build_form_input('branch_name', lang('BranchMaster.field.branch_name'), [
                                'type' => 'text',
                            ], @$branch['branch_name']);
                            echo build_form_input('branch_slug', lang('BranchMaster.field.branch_slug'), [
                                'type'             => 'text',
//                                'data-explanation' => lang('BranchMaster.explanation.branch_slug')
                            ], @$branch['branch_slug']);
                            foreach ($all_languages as $lang_code => $language_name) {
                                echo build_form_input('branch_local_names_' . $lang_code, lang('BranchMaster.field.branch_local_names') . ' (' . $language_name . ')', [
                                    'type' => 'text',
                                ], @$branch['branch_local_names'][$lang_code]);
                            }
                            echo build_form_input('timezone_code', lang('BranchMaster.field.timezone_code'), [
                                'type' => 'select'
                            ], @$branch['timezone_code'], '', $timezones);
                            echo build_form_input('branch_type', lang('BranchMaster.field.branch_type'), [
                                'type' => 'select'
                            ], @$branch['branch_type'], '', [
                                'PHYSICAL' => lang('BranchMaster.enum.branch_type.PHYSICAL'),
                                'ONLINE' => lang('BranchMaster.enum.branch_type.ONLINE')
                            ]);
                            echo build_form_input('branch_address', lang('BranchMaster.field.branch_address'), [
                                'type'             => 'text'
                            ], @$branch['branch_address']);
                            echo build_form_input('branch_postal_code', lang('BranchMaster.field.branch_postal_code'), [
                                'type'             => 'text'
                            ], @$branch['branch_postal_code']);
                            echo build_form_input('branch_status', lang('BranchMaster.field.branch_status'), [
                                'type' => 'select'
                            ], @$branch['branch_status'], '', [
                                'ACTIVE'   => lang('BranchMaster.enum.branch_status.ACTIVE'),
                                'INACTIVE' => lang('BranchMaster.enum.branch_status.INACTIVE')
                            ]);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-master"><?= lang('System.buttons.save') ?></button>
                            </div>
                        </div>
                        <?php if ('edit' == $mode) : ?>
                        <div class="col-12 col-md-8">
                            <h3><?= lang('Business.branch-management.opening-hours') ?></h3>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th><?= lang('Business.branch-management.hours.day') ?></th>
                                        <th><?= lang('Business.branch-management.hours.opens') ?></th>
                                        <th><?= lang('Business.branch-management.hours.closes') ?></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($hours as $d => $hour) : ?>
                                        <tr>
                                            <td><?= lang('Business.branch-management.days.' . $d) ?></td>
                                            <td><label><input type="time" class="form-control branch-opening-hours-<?= $d ?>" name="branch-opening-hours-<?= $d ?>-opn" id="branch-opening-hours-<?= $d ?>-opn" data-id="<?= $hour[0] ?>" data-day="<?= $d ?>" value="<?= $hour[1] ?>"/></label></td>
                                            <td><label><input type="time" class="form-control branch-opening-hours-<?= $d ?>" name="branch-opening-hours-<?= $d ?>-cls" id="branch-opening-hours-<?= $d ?>-cls" data-id="<?= $hour[0] ?>" data-day="<?= $d ?>" value="<?= $hour[2] ?>"/></label></td>
                                            <td class="text-end">
                                                <button class="btn btn-primary btn-sm btn-save-hours" data-target="branch-opening-hours-<?= $d ?>" id="btn-save-hours-<?= $d ?>" data-id="<?= $hour[0] ?>" data-dow="<?= $d ?>"><?= lang('System.buttons.save') ?></button>
                                                <button class="btn btn-outline-danger btn-sm btn-close-day" data-dow="<?= $d ?>"><?= lang('Business.branch-management.close-shop') ?></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <h3><?= lang('Business.branch-management.modified-hours') ?></h3>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th><?= lang('BranchModifiedHours.field.modified_hours_date') ?></th>
                                        <th><?= lang('BranchModifiedHours.field.modified_reason') ?></th>
                                        <th><?= lang('BranchModifiedHours.field.modified_type') ?></th>
                                        <th><?= lang('BranchModifiedHours.field.updated_opening_hours') ?></th>
                                        <th><?= lang('BranchModifiedHours.field.updated_closing_hours') ?></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (empty($modified)) : ?>
                                        <tr>
                                            <td colspan="6" class="text-center">-</td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($modified as $row) : ?>
                                            <tr>
                                                <td><?= format_date($row['modified_hours_date']) ?></td>
                                                <td><?= $row['modified_reason'] ?></td>
                                                <td><?= lang('BranchModifiedHours.enum.modified_type.' . $row['modified_type']) ?></td>
                                                <td><?= empty($row['updated_opening_hours']) ? '' : format_time($row['updated_opening_hours']) ?></td>
                                                <td><?= empty($row['updated_closing_hours']) ? '' : format_time($row['updated_closing_hours']) ?></td>
                                                <td class="text-end">
                                                    <button class="btn btn-outline-danger btn-sm btn-remove-modified-hours" id="btn-remove-modified-hours-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>"><?= lang('System.buttons.remove') ?></button>
                                                    <button class="btn btn-outline-danger btn-sm btn-remove-modified-hours-confirm d-none" id="btn-remove-modified-hours-confirm-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>"><?= lang('System.buttons.remove-confirm') ?></button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <h3><?= lang('Business.branch-management.modified-hours-new') ?></h3>
                            <?php
                            echo build_form_input('modified_hours_date', lang('BranchModifiedHours.field.modified_hours_date'), [
                                'type' => 'date',
                            ], null);
                            echo build_form_input('modified_reason', lang('BranchModifiedHours.field.modified_reason'), [
                                'type' => 'text',
                            ], null);
                            echo build_form_input('modified_type', lang('BranchModifiedHours.field.modified_type'), [
                                'type' => 'select',
                            ], null, '', [
                                'CLOSED'         => lang('BranchModifiedHours.enum.modified_type.CLOSED'),
                                'MODIFIED_HOURS' => lang('BranchModifiedHours.enum.modified_type.MODIFIED_HOURS'),
                            ]);
                            echo build_form_input('updated_opening_hours', lang('BranchModifiedHours.field.updated_opening_hours'), [
                                'type' => 'time',
                            ], null);
                            echo build_form_input('updated_closing_hours', lang('BranchModifiedHours.field.updated_closing_hours'), [
                                'type' => 'time',
                            ], null);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-modified"><?= lang('System.buttons.save') ?></button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <input type="hidden" id="id" name="id" />
                <input type="hidden" id="branch_id" name="branch_id" value="<?= $branch['id'] ?? 0 ?>" />
                <input type="hidden" id="branch_opening_hours_id" name="branch_opening_hours_id" />
                <input type="hidden" id="action_table" name="action_table" />
                <input type="hidden" id="action_perform" name="action_perform" />
                <input type="hidden" id="day_of_the_week" name="day_of_the_week" />
                <input type="hidden" id="opening_hours" name="opening_hours" />
                <input type="hidden" id="closing_hours" name="closing_hours" />
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('#branch_slug').change(function () {
                let slug = $(this).val();
                slug = slug.toLowerCase();
                slug = slug.replace(/[^a-z-]/g, '');
                $(this).val(slug);
            });
            $('#branch_name').change(function () {
                let branch_name = $(this).val();
                $.post(
                    "<?= base_url('helper/generate-slug') ?>",
                    {name: branch_name},
                    function (response) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            $('#branch_slug').val(response.slug);
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
            $('#btn-save-master').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['subdivision_code', 'branch_name', 'branch_slug', 'timezone_code', 'branch_type', 'branch_address', 'branch_postal_code', 'branch_status'];
                foreach ($all_languages as $language_code => $language_name) {
                    $fields[] = 'branch_local_names_' . $language_code;
                }
                gen_js_fields_checker($fields);
                ?>
                $('#btn-save-master').prop('disabled', true);
                $('#id').val(<?= $branch['id'] ?? 0 ?>);
                $('#action_table').val('branch_master');
                $.post(
                    "<?= base_url('admin/business/branch-manage') ?>",
                    <?php $fields[] = 'action_table'; $fields[] = 'id'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save-master').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.href='<?= base_url('admin/business/branch/') ?>'+branch_slug; }, 3000);
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
            $('.btn-save-hours').click(function (e) {
                e.preventDefault();
                let dow = $(this).data('dow');
                $('#branch_opening_hours_id').val($(this).data('id'));
                $('#day_of_the_week').val(dow);
                if ('' == $('#branch-opening-hours-'+dow+'-opn').val()) {
                    $('#branch-opening-hours-'+dow+'-opn').focus();
                    return false;
                }
                if ('' == $('#branch-opening-hours-'+dow+'-cls').val()) {
                    $('#branch-opening-hours-'+dow+'-cls').focus();
                    return false;
                }
                $('#opening_hours').val($('#branch-opening-hours-'+dow+'-opn').val());
                $('#closing_hours').val($('#branch-opening-hours-'+dow+'-cls').val());
                $('#action_table').val('branch_opening_hours');
                <?php $fields = ['action_table', 'branch_opening_hours_id', 'branch_id', 'day_of_the_week', 'opening_hours', 'closing_hours']; ?>
                $.post(
                    "<?= base_url('admin/business/branch-manage') ?>",
                    <?php gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('.btn-save-hours').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('.btn-save-hours').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            })
            $('.btn-close-day').click(function () {
                let dow = $(this).data('dow');
                $('#branch-opening-hours-'+dow+'-opn').val('00:00');
                $('#branch-opening-hours-'+dow+'-cls').val('00:00');
            });
            $('#modified_type').change(function () {
                let modified_type = $(this).val();
                if ('CLOSED' === modified_type) {
                    $('#updated_opening_hours').val('00:00').prop('disabled', true);
                    $('#updated_closing_hours').val('00:00').prop('disabled', true);
                } else {
                    $('#updated_opening_hours').val('').prop('disabled', false);
                    $('#updated_closing_hours').val('').prop('disabled', false);
                }
            });
            $('#btn-save-modified').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['modified_hours_date', 'modified_reason', 'modified_type', 'updated_opening_hours', 'updated_closing_hours'];
                foreach ($all_languages as $language_code => $language_name) {
                    $fields[] = 'branch_local_names_' . $language_code;
                }
                gen_js_fields_checker($fields);
                ?>
                $('#btn-save-modified').prop('disabled', true);
                $('#id').val('0');
                $('#action_table').val('branch_modified_hours');
                $('#action_perform').val('add');
                $.post(
                    "<?= base_url('admin/business/branch-manage') ?>",
                    <?php $fields[] = 'action_table'; $fields[] = 'action_perform'; $fields[] = 'branch_id'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save-modified').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-save-modified').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            $('.btn-remove-modified-hours').click(function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                $('#btn-remove-modified-hours-confirm-'+id).removeClass('d-none');
                $('#btn-remove-modified-hours-'+id).addClass('d-none');
            });
            $('.btn-remove-modified-hours-confirm').click(function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                $('#action_table').val('branch_modified_hours');
                $('#action_perform').val('delete');
                $('#id').val(id);
                $('#btn-remove-modified-hours-confirm-'+id).prop('disabled', true);
                $.post(
                    "<?= base_url('admin/business/branch-manage') ?>",
                    <?php $fields = ['action_table', 'action_perform', 'id']; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        let id = $(this).data('id');
                        $('#btn-remove-modified-hours-confirm-'+id).prop('disabled', false).addClass('d-none');
                        $('#btn-remove-modified-hours-'+id).prop('disabled', false).removeClass('d-none');
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    let id = $(this).data('id');
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    $('#btn-remove-modified-hours-confirm-'+id).prop('disabled', false).addClass('d-none');
                    $('#btn-remove-modified-hours-'+id).prop('disabled', false).removeClass('d-none');
                    toastr.error(message);
                });
            });
        });
    </script>
<?php $this->endSection() ?>