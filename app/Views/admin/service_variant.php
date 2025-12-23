<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2>
                        <?php if ('new' == $mode) : ?>
                            <?= lang('Service.new-variant') ?>
                        <?php else: ?>
                            <?= $service['service_local_names'][$session->lang] ?? $service['service_name'] ?> /
                            <?= $variant['variant_local_names'][$session->lang] ?? $variant['variant_name'] ?>
                        <?php endif; ?>
                    </h2>
                    <div class="row">
                        <div class="col col-md-6">
                            <?php
                            echo build_form_input('variant_name', lang('ServiceVariant.field.variant_name'), [
                                'type' => 'text',
                            ], @$variant['variant_name']);
                            $locales = get_available_locales('long');
                            foreach ($locales as $locale_code => $locale_name) {
                                echo build_form_input('variant_local_names_' . $locale_code, lang('ServiceVariant.field.variant_local_names') . ' (' . $locale_name . ')', [
                                    'type' => 'text',
                                ], @$variant['variant_local_names'][$locale_code]);
                            }
                            echo build_form_input('is_active', lang('ServiceVariant.field.is_active'), [
                                'type' => 'select',
                            ], @$variant['is_active'], '', [
                                'A' => lang('ServiceVariant.enum.is_active.A'),
                                'I' => lang('ServiceVariant.enum.is_active.I'),
                            ]);
                            echo build_form_input('schedule_type', lang('ServiceVariant.field.schedule_type'), [
                                'type' => 'select',
                            ], @$variant['schedule_type'], '', [
                                'A' => lang('ServiceVariant.enum.schedule_type.A'),
                                'S' => lang('ServiceVariant.enum.schedule_type.S'),
                            ]);
                            echo build_form_input('variant_capacity', lang('ServiceVariant.field.variant_capacity'), [
                                'type' => 'number',
                                'min'  => 1
                            ], @$variant['variant_capacity']);
                            echo build_form_input('required_num_staff', lang('ServiceVariant.field.required_num_staff'), [
                                'type' => 'number',
                                'min'  => 1
                            ], @$variant['required_num_staff']);
                            echo build_form_input('service_duration_minutes', lang('ServiceVariant.field.service_duration_minutes'), [
                                'type' => 'number',
                                'min'  => 1
                            ], @$variant['service_duration_minutes']);
                            echo build_form_input('required_resource_type_id', lang('ServiceVariant.field.required_resource_type_id'), [
                                'type' => 'select'
                            ], @$variant['required_resource_type_id'], '', $resourceTypes);
                            echo build_form_input('price_active', lang('ServiceVariant.field.price_active'), [
                                'type' => 'number',
                                'min'  => 1,
                            ], @$variant['price_active']);
                            echo build_form_input('price_compare', lang('ServiceVariant.field.price_compare'), [
                                'type' => 'number',
                                'min'  => 1,
                            ], @$variant['price_compare']);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <input type="hidden" name="id" id="id" value="<?= $variant['id'] ?? 0 ?>" />
                            <input type="hidden" name="service_id" id="service_id" value="<?= $service['id'] ?? 0 ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('#btn-save').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['variant_name', 'is_active', 'schedule_type', 'variant_capacity', 'required_num_staff', 'service_duration_minutes', 'required_resource_type_id', 'price_active', 'price_compare'];
                foreach ($locales as $code => $language_name) {
                    $fields[] = 'variant_local_names_' . $code;
                }
                gen_js_fields_checker($fields);
                ?>
                $('#btn-save').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/service/variant/manage') ?>",
                    <?php $fields[] = 'id'; $fields[] = 'service_id'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.href='<?= base_url('admin/service/' . ($service['id']*ID_MASKED_PRIME)) ?>'; }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-save').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
        });
    </script>
<?php $this->endSection() ?>