<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('OrderMaster.field.order_number') ?></th>
                                <th><?= lang('CustomerMaster.field.customer_name') ?></th>
                                <th><?= lang('OrderMaster.field.order_total') ?></th>
                                <th><?= lang('OrderMaster.field.shipping_option') ?></th>
                                <th><?= lang('OrderMaster.field.payment_method') ?></th>
                                <th><?= lang('OrderMaster.field.order_status') ?></th>
                                <th><?= lang('OrderMaster.field.financial_status') ?></th>
                                <th><?= lang('OrderMaster.field.shipping_status') ?></th>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = $('table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader: true,
                searching: true,
                ordering: true,
                <?php if ('en' != $lang) : ?>
                language: {url: '<?= base_url('/assets/vendor/DataTables/i18n/' . $lang . '.json') ?>',},
                <?php endif; ?>
                ajax: {
                    url: '<?= base_url('/admin/order') ?>',
                    type: 'POST',
                    data: function (data) {}
                }
            });
        });
    </script>
<?php $this->endSection() ?>