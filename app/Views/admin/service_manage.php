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
                            echo build_form_input('service_slug', lang('ServiceMaster.field.service_slug'), [
                                'type' => 'text',
                            ], @$service['service_slug']);
                            $locales = get_available_locales('long');
                            foreach ($locales as $locale_code => $locale_name) {
                                echo build_form_input('service_local_names_' . $locale_code, lang('ServiceMaster.field.service_local_names') . ' (' . $locale_name . ')', [
                                    'type' => 'text',
                                ], @$service['service_local_names'][$locale_code]);
                            }
                            echo build_form_input('is_active', lang('ServiceMaster.field.is_active'), [
                                'type' => 'select',
                            ], @$service['is_active'], '', [
                                'A' => lang('ServiceMaster.enum.is_active.A'),
                                'I' => lang('ServiceMaster.enum.is_active.I'),
                            ]);
                            echo build_form_input('price_active_lowest', lang('ServiceMaster.field.price_active_lowest'), [
                                'type'     => 'text',
                                'readonly' => 'readonly',
                                'data-explanation' => lang('ServiceMaster.explanation.price_active_lowest')
                            ], @$service['price_active_lowest']);
                            echo build_form_input('price_compare_lowest', lang('ServiceMaster.field.price_compare_lowest'), [
                                'type'     => 'text',
                                'readonly' => 'readonly',
                                'data-explanation' => lang('ServiceMaster.explanation.price_compare_lowest')
                            ], @$service['price_compare_lowest']);
                            ?>
                        </div>
                    </div>

                    <pre>
                        <?php print_r($variants); ?>
                    </pre>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>