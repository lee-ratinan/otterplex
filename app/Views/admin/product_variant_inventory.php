<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= $itemTitle ?></h2>
                    <p><?= lang('Product.inventory-count', [$variant['inventory_count']]) ?></p>
                    <div class="row">
                        <div class="col">
                            <label for="filter-start"><?= lang('Product.inventory-filter-start') ?></label>
                            <input class="form-control" type="date" id="filter-start" value="<?= date('Y-m-01') ?>" />
                        </div>
                        <div class="col">
                            <label for="filter-end"><?= lang('Product.inventory-filter-end') ?></label>
                            <input class="form-control" type="date" id="filter-end" value="<?= date('Y-m-t') ?>" />
                        </div>
                        <div class="col">
                            <br>
                            <button id="btn-filter" class="btn btn-primary w-100"> <?= lang('System.buttons.filter') ?></button>
                        </div>
                    </div>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = $('table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader: true,
                searching: false,
                ordering: false,
                <?php if ('en' != $lang) : ?>
                language: {url: '<?= base_url('/assets/vendor/DataTables/i18n/' . $lang . '.json') ?>',},
                <?php endif; ?>
                ajax: {
                    url: '<?= base_url('/admin/product/variant/inventory/' . ($variant['product_id'] * ID_MASKED_PRIME) . '/' . ($variant['id'] * ID_MASKED_PRIME)) ?>',
                    type: 'POST',
                    data: function (data) {
                        data.start_date = $('#filter-start').val();
                        data.end_date   = $('#filter-end').val();
                    }
                }
            });
            $('#btn-filter').click(function (e) {
                e.preventDefault();
                table.draw();
            });
        });
    </script>
<?php $this->endSection() ?>