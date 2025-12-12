<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="alert alert-info"><i class="bi bi-info-circle me-3"></i> <?= lang('Business.customer-management.privacy-policy') ?></div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('CustomerMaster.field.email_address') ?></th>
                                <th><?= lang('CustomerMaster.field.telephone_number') ?></th>
                                <th><?= lang('CustomerMaster.field.customer_name') ?></th>
                                <th><?= lang('CustomerMaster.field.is_active') ?></th>
                                <th><?= lang('CustomerAddress.field.address_city') ?></th>
                                <th><?= lang('CustomerAddress.field.country_code') ?></th>
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
                    url: '<?= base_url('/admin/business/customer') ?>',
                    type: 'POST',
                    data: function (data) {}
                }
            });
        });
    </script>
<?php $this->endSection() ?>