<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-end">
                        <a class="btn btn-primary" href="<?= base_url('admin/service/0') ?>">
                            <i class="fa-solid fa-circle-plus"></i>
                            <?= lang('Service.new-service') ?>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('ServiceMaster.field.service_slug') ?></th>
                                <th><?= lang('ServiceMaster.field.service_name') ?></th>
                                <th><?= lang('ServiceMaster.field.is_active') ?></th>
                                <th class="text-end"><?= lang('ServiceMaster.field.price_active_lowest') ?></th>
                                <th class="text-end"><?= lang('ServiceMaster.field.price_compare_lowest') ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($services as $service) : ?>
                            <tr>
                                <td><?= $service['service_slug'] ?></td>
                                <td><?= $service['service_local_name'][$session->lang] ?? $service['service_name'] ?></td>
                                <td><?= lang('ServiceMaster.enum.is_active.' . $service['is_active']) ?></td>
                                <td class="text-end" data-sort="<?= $service['price_active_lowest'] ?>"><?= format_price($service['price_active_lowest'], $session->business['currency_code']) ?></td>
                                <td class="text-end" data-sort="<?= $service['price_compare_lowest'] ?>"><?= format_price($service['price_compare_lowest'], $session->business['currency_code']) ?></td>
                                <td><a class="btn btn-primary btn-sm" href="<?= base_url('admin/service/' . ($service['id'] * ID_MASKED_PRIME)) ?>"> <?= lang('System.buttons.edit') ?></a></td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>