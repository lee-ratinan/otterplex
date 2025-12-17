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
//                            echo build_form_input('price_active_lowest', lang('ServiceMaster.field.price_active_lowest'), [
//                                'type'     => 'text',
//                                'readonly' => 'readonly',
//                                'data-explanation' => lang('ServiceMaster.explanation.price_active_lowest')
//                            ], @$service['price_active_lowest']);
//                            echo build_form_input('price_compare_lowest', lang('ServiceMaster.field.price_compare_lowest'), [
//                                'type'     => 'text',
//                                'readonly' => 'readonly',
//                                'data-explanation' => lang('ServiceMaster.explanation.price_compare_lowest')
//                            ], @$service['price_compare_lowest']);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-master"><?= lang('System.buttons.save') ?></button>
                            </div>
                        </div>
                    </div>
                    <?php if ('edit' == $mode) : ?>
                    <h2><?= lang('Service.service-variant') ?></h2>
                    <div class="text-end">
                        <a class="btn btn-primary" href="<?= base_url('service/variant/0') ?>"><i class="fa-solid fa-plus-circle"></i> <?= lang('Service.new-variant') ?></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('ServiceVariant.field.variant_slug') ?></th>
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
                                <td><?= $variant['variant_slug'] ?></td>
                                <td><?= ($variant['variant_local_names'][$session->lang] ?? $variant['variant_name']) ?></td>
                                <td><?= lang('ServiceVariant.enum.is_active.' . $variant['is_active']) ?></td>
                                <td><?= lang('ServiceVariant.enum.schedule_type.' . $variant['schedule_type']) ?></td>
                                <td><?= format_price($variant['price_active'], $session->business['currency_code']) ?></td>
                                <td><?= format_price($variant['price_compare'], $session->business['currency_code']) ?></td>
                                <td><?= lang('Service.num-staff', [$variant['required_num_staff']]) ?></td>
                                <td><?= lang('Service.duration-minutes', [$variant['service_duration_minutes']]) ?></td>
                                <td><?= $variant['resource_type'] ?></td>
                                <td><a class="btn btn-primary btn-sm float-end" href="<?= base_url('service/variant/' . ($variant['id'] * ID_MASKED_PRIME)) ?>"><?= lang('System.buttons.edit') ?></a></td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <h2><?= lang('Service.service-staff') ?></h2>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>