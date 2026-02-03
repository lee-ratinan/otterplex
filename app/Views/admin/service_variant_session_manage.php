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
                    <input type="hidden" id="action" name="action" value="" />
                    <input type="hidden" id="session_master_id" name="session_master_id" value="<?= $session_data['id'] ?? 0 ?>" />
                    <input type="hidden" id="session_master_session_type" name="session_master_session_type" value="OPEN" />
                    <input type="hidden" id="session_master_service_variant_id" name="session_master_service_variant_id" value="<?= $variant['id'] ?>" />
                    <input type="hidden" id="session_master_date_start" name="session_master_date_start" value="<?= $session_data['date_start'] ?? '' ?>" />
                    <input type="hidden" id="session_master_date_end" name="session_master_date_end" value="<?= $session_data['date_end'] ?? '' ?>" />
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
                                        <th><?= (empty($resource_type) ? lang('ResourceMaster.field.resource_type_id') : $resource_type) ?></th>
                                        <th><?= lang('Service.service-staff') ?></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $totalMinutes = 0; ?>
                                    <?php foreach ($sessions_list as $row) : ?>
                                        <?php
                                        $utcTz    = new \DateTimeZone('UTC');
                                        $branchTz = new \DateTimeZone($branch_tz);
                                        $start    = new \DateTime($row['time_start'], $utcTz);
                                        $end      = new \DateTime($row['time_end'], $utcTz);
                                        $diff     = $end->diff($start);
                                        $minutes  = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                                        $totalMinutes += $minutes;
                                        $start->setTimezone($branchTz);
                                        $end->setTimezone($branchTz);
                                        ?>
                                        <tr>
                                            <td><?= format_date_time($start->format('Y-m-d H:i:s'), $lang) ?></td>
                                            <td><?= format_date_time($end->format('Y-m-d H:i:s'), $lang) ?></td>
                                            <td><?= generate_duration_label($minutes) ?></td>
                                            <td><?= $resource_allocations[$row['id']] ?? '-' ?></td>
                                            <td><?= $staff_allocations[$row['id']] ?? '-' ?></td>
                                            <td>
                                                <button class="btn btn-outline-danger btn-sm float-end btn-remove-session-breakdown" id="btn-remove-breakdown-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>"><?= lang('System.buttons.remove') ?></button>
                                                <button class="btn btn-outline-danger btn-sm float-end btn-remove-session-breakdown-confirm d-none" id="btn-remove-breakdown-confirm-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>"><?= lang('System.buttons.remove-confirm') ?></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th><?= generate_duration_label($totalMinutes) ?></th>
                                        <th colspan="3"></th>
                                    </tr>
                                    </tfoot>
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
                                    echo '<input type="hidden" id="session_breakdown_resource_master_id" name="session_breakdown_resource_master_id" value="0" />';
                                }
                                if (1 == $variant['required_num_staff']) {
                                    echo build_form_input('session_breakdown_staff_user_id', lang('Service.service-staff'), [
                                        'type' => 'select'
                                    ], '', '', $staff_options);
                                } else {
                                    echo '<input type="hidden" id="session_breakdown_staff_user_id" name="session_breakdown_staff_user_id" value="0" />';
                                }
                                ?>
                                <input type="hidden" id="session_breakdown_session_master_id" name="session_breakdown_session_master_id" value="<?= $session_data['id'] ?>" />
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
                $fields = ['session_master_id', 'session_master_session_type', 'session_master_service_variant_id', 'session_master_branch_id', 'session_master_session_capacity', 'session_master_short_description'];
                gen_js_fields_checker($fields);
                $fields[] = 'session_master_date_start';
                $fields[] = 'session_master_date_end';
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
            // SAVE
            $('#btn-add-breakdown').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['session_breakdown_date_start', 'session_breakdown_time_start', 'session_breakdown_date_end', 'session_breakdown_time_end', 'session_breakdown_resource_master_id', 'session_breakdown_staff_user_id', 'session_breakdown_session_master_id'];
                gen_js_fields_checker($fields);
                ?>
                $('#action').val('session_breakdown');
                $('#btn-add-breakdown').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/service/variant/session/manage') ?>",
                    <?php $fields[] = 'action'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-add-breakdown').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-add-breakdown').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            $('.btn-remove-session-breakdown').click(function (e) {
                e.preventDefault();
                let target_id = '#btn-remove-breakdown-confirm-' + $(this).data('id');
                $(target_id).removeClass('d-none');
                $(this).addClass('d-none');
            })
            $('.btn-remove-session-breakdown-confirm').click(function (e) {
                e.preventDefault();
                $(this).prop('disabled', true);
                let session_breakdown_id = $(this).data('id');
                $.post(
                    "<?= base_url('admin/service/variant/session/manage') ?>",
                    {action:'remove_session_breakdown', session_breakdown_id: session_breakdown_id},
                    function (response, status) {
                        $('#btn-remove-breakdown-confirm-'+session_breakdown_id).prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-remove-breakdown-confirm-'+session_breakdown_id).prop('disabled', false).addClass('d-none');
                    $('#btn-remove-breakdown-'+session_breakdown_id).removeClass('d-none');
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            <?php endif; ?>
        });
    </script>
<?php $this->endSection() ?>