<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col">
                            <div class="text-end">
                                <?php if (0 < $count) : ?>
                                <a class="btn btn-primary" href="<?= base_url('admin/product/0') ?>">
                                    <i class="fa-solid fa-circle-plus"></i> <?= lang('Product.new-product') ?>
                                </a>
                                <?php else : ?>
                                    <i class="fa-solid fa-info-circle"></i> <?= lang('Product.add-product-category-first') ?>
                                <?php endif; ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th><?= lang('ProductMaster.field.product_category_id') ?></th>
                                        <th><?= lang('ProductMaster.field.product_name') ?></th>
                                        <th><?= lang('ProductMaster.field.product_type') ?></th>
                                        <th><?= lang('ProductMaster.field.is_active') ?></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = $('table').DataTable({
                processing: false,
                serverSide: false,
                fixedHeader: true,
                searching: true,
                ordering: true,
                <?php if ('en' != $lang) : ?>
                language: {url: '<?= base_url('/assets/vendor/DataTables/i18n/' . $lang . '.json') ?>',},
                <?php endif; ?>
                ajax: {
                    url: '<?= base_url('/admin/product') ?>',
                    type: 'POST',
                    data: function (data) {}
                }
            });
        });
    </script>
<?php $this->endSection() ?>