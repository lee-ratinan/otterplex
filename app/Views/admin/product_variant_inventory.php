<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= $itemTitle ?></h2>
                    <p><?= lang('Product.inventory-count', [$variant['inventory_count']]) ?></p>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th><?= lang('ProductVariantInventory.field.activity_key') ?></th>
                                <th><?= lang('ProductVariantInventory.field.quantity_change') ?></th>
                                <th><?= lang('ProductVariantInventory.field.new_inventory') ?></th>
                                <th><?= lang('System.generic-field.created_at') ?></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>