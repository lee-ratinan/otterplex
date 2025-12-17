<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= ('edit' == $mode ? ($variant['variant_local_names'][$session->lang] ?? $variant['variant_name']) : lang('Service.new-variant')) ?></h2>
                    <div class="row">
                        <div class="col col-md-6">
                            <?php
                            echo build_form_input('variant_name', lang('ServiceVariant.field.variant_name'), [
                                'type' => 'text',
                            ], @$variant['variant_name']);
                            echo build_form_input('variant_slug', lang('ServiceVariant.field.variant_slug'), [
                                'type'     => 'text',
                                'readonly' => 'readonly',
                            ], @$variant['variant_slug']);
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
                            ], @$variant['variant_name'], '', [
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
                                <button class="btn btn-primary" id="btn-save-master"><?= lang('System.buttons.save') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>