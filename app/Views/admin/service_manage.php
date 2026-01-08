<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= ('new' == $mode ? lang('Service.new-service') : $service['service_local_names'][$session->lang] ?? $service['service_name']) ?></h2>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <?php
                            echo build_form_input('service_name', lang('ServiceMaster.field.service_name'), [
                                'type' => 'text',
                            ], @$service['service_name']);
                            $locales = get_available_locales('long');
                            foreach ($locales as $locale_code => $locale_name) {
                                echo build_form_input('service_local_names_' . $locale_code, lang('ServiceMaster.field.service_local_names') . ' (' . $locale_name . ')', [
                                    'type' => 'text',
                                ], @$service['service_local_names'][$locale_code]);
                            }
                            foreach ($locales as $locale_code => $locale_name) {
                                echo build_form_input('service_description_' . $locale_code, lang('ServiceMaster.field.service_description') . ' (' . $locale_name . ')', [
                                    'type' => 'text',
                                ], @$service['service_description'][$locale_code]);
                            }
                            echo build_form_input('is_active', lang('ServiceMaster.field.is_active'), [
                                'type' => 'select',
                            ], @$service['is_active'], '', [
                                'A' => lang('ServiceMaster.enum.is_active.A'),
                                'I' => lang('ServiceMaster.enum.is_active.I'),
                            ]);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-master"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <input type="hidden" name="service_id" id="service_id" value="<?= $service['id'] ?? 0 ?>" />
                        </div>
                    </div>
                    <?php if ('edit' == $mode) : ?>
                        <!-- UPLOAD SERVICE IMAGE -->
                        <hr />
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <h3><?= lang('Service.upload-image') ?></h3>
                                <form id="form-upload-image" action="<?= base_url('/admin/service/manage') ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="script_action" value="upload_image"/>
                                    <input type="file" id="service_image" name="service_image" class="form-control my-3"/>
                                    <input type="hidden" id="slug_for_image" name="slug_for_image" value="<?= $service['service_slug'] ?>"/>
                                    <input type="hidden" id="id_for_image" name="id_for_image" value="<?= $service['id'] ?>"/>
                                    <p class="small"><?= lang('Service.upload-explanation') ?></p>
                                    <div class="text-end">
                                        <button id="btn-upload-image" type="submit" class="btn btn-primary"><?= lang('System.buttons.upload') ?></button>
                                        <button id="btn-remove-image" type="button" class="btn btn-outline-danger"><?= lang('System.buttons.remove') ?></button>
                                        <button id="btn-remove-image-confirm" type="button" class="btn btn-outline-danger" style="display:none"><?= lang('System.buttons.remove-confirm') ?></button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 col-md-6">
                                <?php if (!empty($service['service_image'])) : ?>
                                    <img src="<?= base_url('file/' . $service['service_image']) ?>" class="img-fluid" />
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr/>
                        <h2><?= lang('Service.service-variant') ?></h2>
                        <div class="text-end">
                            <a class="btn btn-primary" href="<?= base_url('admin/service/variant/' . ($service['id'] * ID_MASKED_PRIME) . '/0') ?>"><i class="fa-solid fa-plus-circle"></i> <?= lang('Service.new-variant') ?></a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped">
                                <thead>
                                <tr>
                                    <th><?= lang('ServiceVariant.field.variant_name') ?></th>
                                    <th><?= lang('ServiceVariant.field.is_active') ?></th>
                                    <th><?= lang('ServiceVariant.field.schedule_type') ?></th>
                                    <th><?= lang('ServiceVariant.field.price_active') ?></th>
                                    <th><?= lang('ServiceVariant.field.price_compare') ?></th>
                                    <th><?= lang('ServiceVariant.field.required_num_staff') ?></th>
                                    <th><?= lang('ServiceVariant.field.service_duration_minutes') ?></th>
                                    <th><?= lang('ServiceVariant.field.required_resource_type_id') ?></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($variants as $variant): ?>
                                    <tr>
                                        <td><?= ($variant['variant_local_names'][$session->lang] ?? $variant['variant_name']) ?></td>
                                        <td><?= lang('ServiceVariant.enum.is_active.' . $variant['is_active']) ?></td>
                                        <td><?= lang('ServiceVariant.enum.schedule_type.' . $variant['schedule_type']) ?></td>
                                        <td><?= format_price($variant['price_active'], $session->business['currency_code']) ?></td>
                                        <td><?= format_price($variant['price_compare'], $session->business['currency_code']) ?></td>
                                        <td><?= lang('Service.num-staff', [$variant['required_num_staff']]) ?></td>
                                        <td><?= lang('Service.duration-minutes', [$variant['service_duration_minutes']]) ?></td>
                                        <td><?= $variant['resource_type'] ?></td>
                                        <td class="text-end">
                                            <?php if ('S' == $variant['schedule_type']) : ?>
                                                <a class="btn btn-primary btn-sm mb-1" href="<?= base_url('admin/service/variant/session/' . ($variant['service_id'] * ID_MASKED_PRIME) . '/' . ($variant['id'] * ID_MASKED_PRIME)) ?>"><i class="fa-solid fa-eye"></i> <?= lang('Service.session.view-btn') ?></a>
                                            <?php endif; ?>
                                            <a class="btn btn-primary btn-sm mb-1 ms-1" href="<?= base_url('admin/service/variant/' . ($variant['service_id'] * ID_MASKED_PRIME) . '/' . ($variant['id'] * ID_MASKED_PRIME)) ?>"><?= lang('System.buttons.edit') ?></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <h2><?= lang('Service.service-staff') ?></h2>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped">
                                <thead>
                                <tr>
                                    <th><?= lang('BranchMaster.field.branch_name') ?></th>
                                    <th><?= lang('UserMaster.field.user_full_name') ?></th>
                                    <th><?= lang('BranchUser.field.user_role') ?></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($staff as $row): ?>
                                    <tr>
                                        <td><?= $row['branch_local_names'][$session->lang] ?? $row['branch_name'] ?></td>
                                        <td><?= $row['user_name_first'] . ' ' . $row['user_name_last'] ?></td>
                                        <td><?= lang('BranchUser.enum.user_role.' . $row['user_role']) ?></td>
                                        <td class="text-end">
                                            <button class="btn btn-outline-danger btn-sm btn-staff-remove" id="btn-staff-remove-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>"><?= lang('System.buttons.remove') ?></button>
                                            <button class="btn btn-outline-danger btn-sm btn-staff-remove-confirm d-none" id="btn-staff-remove-confirm-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>"><?= lang('System.buttons.remove-confirm') ?></button>
                                        </td>
                                    </tr>
                                    <?php unset($staffList[$row['branch_user_id']]); ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (!empty($staffList)) : ?>
                            <h2><?= lang('Service.add-service-staff') ?></h2>
                            <div class="row">
                                <div class="col col-md-6">
                                    <?php
                                    echo build_form_input('branch_user_id', lang('UserMaster.field.user_full_name'), [
                                        'type' => 'select'
                                    ], null, '', $staffList);
                                    ?>
                                    <div class="text-end">
                                        <button class="btn btn-primary" id="btn-save-staff"><?= lang('System.buttons.save') ?></button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = $('table').DataTable();
            // SAVE
            $('#btn-save-master').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['service_name', 'is_active'];
                foreach ($locales as $code => $language_name) {
                    $fields[] = 'service_local_names_' . $code;
                }
                gen_js_fields_checker($fields);
                foreach ($locales as $code => $language_name) {
                    $fields[] = 'service_description_' . $code;
                }
                ?>
                $('#btn-save-master').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/service/manage') ?>",
                    <?php $fields[] = 'service_id'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save-master').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            let id = response.id * <?= ID_MASKED_PRIME ?>;
                            setTimeout(function() { location.href='<?= base_url('admin/service/') ?>' + id; }, 3000);
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
            $('#btn-save-staff').click(function (e) {
                e.preventDefault();
                let branch_user_id = $('#branch_user_id').val();
                if ('' === branch_user_id) {
                    $('#branch_user_id').focus();
                    return false;
                }
                let service_id = <?= $service['id'] ?? 0 ?>;
                $('#btn-save-staff').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/service/user/manage') ?>",
                    {branch_user_id: branch_user_id, service_id: service_id, action: 'add'},
                    function (response, status) {
                        $('#btn-save-staff').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-save-staff').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            $('.btn-staff-remove').click(function () {
                let id = $(this).data('id');
                $('#btn-staff-remove-confirm-'+id).removeClass('d-none');
                $(this).addClass('d-none');
            });
            $('.btn-staff-remove-confirm').click(function () {
                let id = $(this).data('id');
                $(this).prop('disabled', true);
                $.post(
                    "<?= base_url('admin/service/user/manage') ?>",
                    {id: id, action: 'remove'},
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
            // IMAGE
            $('#btn-upload-image').on('click', function (e) {
                e.preventDefault();
                // check if the file is selected
                if ($('#service_image').val() === '') {
                    toastr.warning('<?= lang('System.response-msg.error.please-check-empty-field') ?>');
                    $('#service_image').focus();
                    return;
                }
                $('#btn-upload-image').prop('disabled', true);
                // submit #form-upload-avatar form in AJAX
                $.ajax({
                    url: '<?= base_url('/admin/service/manage') ?>',
                    type: 'POST',
                    data: new FormData($('#form-upload-image')[0]),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        $('#btn-upload-image').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#btn-upload-image').prop('disabled', false);
                        let response = JSON.parse(xhr.responseText);
                        let message = response.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                        toastr.error(message);
                    }
                });
            });
            $('#btn-remove-image').on('click', function (e) {
                e.preventDefault();
                $('#btn-remove-image').hide();
                $('#btn-remove-image-confirm').show();
            });
            $('#btn-remove-image-confirm').on('click', function (e) {
                e.preventDefault();
                $('#btn-remove-image-confirm').prop('disabled', true);
                $.ajax({
                    url: '<?= base_url('/admin/service/manage') ?>',
                    type: 'POST',
                    data: {
                        script_action: 'remove_image',
                        service_image: '<?= @$service['service_image'] ?>',
                        id_for_image: '<?= @$service['id'] ?>'
                    },
                    success: function (response) {
                        $('#btn-remove-image-confirm').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#btn-remove-image-confirm').prop('disabled', false);
                        let response = JSON.parse(xhr.responseText);
                        let message = response.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                        toastr.error(message);
                    }
                });
            });
        });
    </script>
<?php $this->endSection() ?>