<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2>
                        <?= $service['service_local_names'][$lang] ?? $service['service_name'] ?><br>
                        <?= $variant['variant_local_names'][$lang] ?? $variant['variant_name'] ?>
                        <?php if (isset($session_data['short_description'])) { echo '<br>' . $session_data['short_description']; } ?>
                    </h2>
                    <p><?= lang('Service.session.explanation') ?></p>
                    <p><?= lang('ServiceVariant.field.service_duration_minutes') ?>: <?= generate_duration_label($variant['service_duration_minutes']) ?></p>
                    <?php $tomorrow = date('Y-m-d', strtotime('tomorrow')); ?>
                    <input type="hidden" id="action" name="action" value="" />
                    <input type="hidden" id="session_master_id" name="session_master_id" value="<?= $session_data['id'] ?? 0 ?>" />
                    <input type="hidden" id="session_master_session_type" name="session_master_session_type" value="OPEN" />
                    <input type="hidden" id="session_master_service_variant_id" name="session_master_service_variant_id" value="<?= $variant['id'] ?>" />
                    <input type="hidden" id="session_master_date_start" name="session_master_date_start" value="<?= $session_data['date_start'] ?? $tomorrow ?>" />
                    <input type="hidden" id="session_master_date_end" name="session_master_date_end" value="<?= $session_data['date_end'] ?? $tomorrow ?>" />
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <?php
                            $capacity = ($session_data['session_capacity'] ?? $variant['variant_capacity']);
                            echo build_form_input('session_master_branch_id', lang('SessionMaster.field.branch_id'), [
                                'type' => 'select',
                            ], @$session_data['branch_id'], '', $branches);
                            echo build_form_input('session_master_session_capacity', lang('SessionMaster.field.session_capacity'), [
                                'type' => 'number',
                                'min'  => 1
                            ], $capacity);
                            echo build_form_input('session_master_short_description', lang('SessionMaster.field.short_description'), [
                                'type'             => 'text',
                                'data-explanation' => lang('SessionMaster.explanation.short_description'),
                            ], @$session_data['short_description']);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-master"><?= lang('System.buttons.save') ?></button>
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <?php if ('edit' == $mode) : ?>
                                <h3 class="mt-5 pt-5"><?= lang('Service.breakdown-list') ?></h3>
                                <table id="session_breakdown_table" class="table table-hover table-striped table-sm">
                                    <thead>
                                    <tr>
                                        <th><?= lang('SessionBreakdown.field.time_start') ?></th>
                                        <th><?= lang('SessionBreakdown.field.time_end') ?></th>
                                        <th><?= lang('Service.duration') ?></th>
                                        <th><?= $resource_type ?></th>
                                        <th><?= lang('Service.service-staff') ?></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($sessions_list as $row) : ?>
                                        <tr>
                                            <td><?= format_date($row['date_start'], $lang) ?></td>
                                            <td><?= format_date($row['date_end'], $lang) ?></td>
                                            <td>...</td>
                                            <td>...</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm float-end btn-remove-session-breakdown" id="btn-remove-breakdown-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>"><?= lang('System.buttons.remove') ?></button>
                                                <button class="btn btn-primary btn-sm float-end btn-remove-session-breakdown-confirm d-none" id="btn-remove-breakdown-confirm-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>"><?= lang('System.buttons.remove-confirm') ?></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <h3 class="mt-5 pt-5"><?= lang('Service.breakdown-list-add') ?></h3>
                                <div class="row">
                                    <div class="col"><?= build_form_input('session_breakdown_date_start', lang('SessionBreakdown.field.date_start'), ['type' => 'date', 'min'  => date('Y-m-d')]); ?></div>
                                    <div class="col"><?= build_form_input('session_breakdown_time_start', lang('SessionBreakdown.field.time_start'), ['type' => 'time']); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col"><?= build_form_input('session_breakdown_date_end', lang('SessionBreakdown.field.date_end'), ['type' => 'date', 'min'  => date('Y-m-d')]); ?></div>
                                    <div class="col"><?= build_form_input('session_breakdown_time_end', lang('SessionBreakdown.field.time_end'), ['type' => 'time']); ?></div>
                                </div>
                                <?php
                                if (!empty($resource_type)) {
                                    echo build_form_input('session_breakdown_resource_master_id', $resource_type, [
                                        'type' => 'select'
                                    ], '', '', $resource_options);
                                } else {
                                    echo '<input type="text" id="session_breakdown_resource_master_id" name="session_breakdown_resource_master_id" value="0" />';
                                }
                                if (1 == $variant['required_num_staff']) {
                                    echo build_form_input('session_breakdown_staff_user_id', lang('Service.service-staff'), [
                                        'type' => 'select'
                                    ], '', '', $staff_options);
                                } else {
                                    echo '<input type="text" id="session_breakdown_staff_user_id" name="session_breakdown_staff_user_id" value="0" />';
                                }
                                ?>
                                <div class="text-end">
                                    <button class="btn btn-primary" id="btn-add-breakdown"><?= lang('System.buttons.save') ?></button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // SAVE
            $('#btn-save-master').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['session_master_id', 'session_master_session_type', 'session_master_service_variant_id', 'session_master_date_start', 'session_master_date_end', 'session_master_branch_id', 'session_master_session_capacity', 'session_master_short_description'];
                gen_js_fields_checker($fields);
                ?>
                $('#action').val('session_master');
                $('#btn-save-master').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/service/variant/session/manage') ?>",
                    <?php $fields[] = 'action'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save-master').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            let id = response.id * <?= ID_MASKED_PRIME ?>;
                            setTimeout(function() { location.href='<?= base_url('admin/service/variant/session/' . $url_ids . '/') ?>' + id; }, 3000);
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
            <?php if ('edit' == $mode) : ?>
            const table = $('#session_breakdown_table').DataTable({
                ordering: false,
                searching: false,
                paging: false,
            });
            // session_breakdown
            $('#session_breakdown_date_start').change(function () {
                let start = $(this).val();
                $('#session_breakdown_date_end').attr('min', start).val(start);
            })
            <?php endif; ?>
        });
    </script>
<?php $this->endSection() ?>