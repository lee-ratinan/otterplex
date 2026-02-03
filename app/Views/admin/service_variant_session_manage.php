<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2>
                        <?= lang('Admin.pages.service-variant-session-manage') ?>:
                        <?= $service['service_local_names'][$lang] ?? $service['service_name'] ?><br>
                        <?= $variant['variant_local_names'][$lang] ?? $variant['variant_name'] ?>
                    </h2>
                    <p><?= lang('Service.session.explanation') ?></p>
                    <p><?= lang('ServiceVariant.field.service_duration_minutes') ?>: <?= generate_duration_label($variant['service_duration_minutes']) ?></p>
                    <?php $tomorrow = date('Y-m-d', strtotime('tomorrow')); ?>
                    <input type="hidden" id="session_master_session_type" name="session_master_session_type" value="OPEN" />
                    <input type="hidden" id="session_master_service_variant_id" name="session_master_service_variant_id" value="<?= $variant['id'] ?>" />
                    <input type="hidden" id="session_master_date_start" name="session_master_date_start" value="<?= $session_data['date_start'] ?? $tomorrow ?>" />
                    <input type="hidden" id="session_master_date_end" name="session_master_date_end" value="<?= $session_data['date_end'] ?? $tomorrow ?>" />
                    <div class="row">
                        <div class="col col-md-6">
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
                    </div>

                    <pre>
                    session_breakdown
                    id int AI PK
                    session_id int FK
                    time_start datetime
                    time_end datetime

                    pick staff: <?= $variant['required_num_staff'] ?>
                    need staff list here...
                    pick resource of type <?= $variant['required_resource_type_id'] ?>
                    need resources of this type here...



                        <?php print_r($service) ?>
                        <?php print_r($variant) ?>
                    </pre>
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
                $fields = ['session_master_session_type', 'session_master_service_variant_id', 'session_master_date_start', 'session_master_date_end', 'session_master_branch_id', 'session_master_session_capacity', 'session_master_short_description'];
                gen_js_fields_checker($fields);
                ?>
                $('#btn-save-master').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/service/variant/session/manage') ?>",
                    <?php gen_json_fields_to_fields($fields) ?>,
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
        });
    </script>
<?php $this->endSection() ?>