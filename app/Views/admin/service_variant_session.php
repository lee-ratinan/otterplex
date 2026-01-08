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
                                <a class="btn btn-primary" href="<?= base_url('admin/service/variant/session/' . $serviceIdMask . '/' . $variantIdMask . '/0') ?>">
                                    <i class="fa-solid fa-circle-plus"></i> <?= lang('Service.session.new-session') ?>
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th><?= lang('SessionMaster.field.id') ?></th>
                                        <th><?= lang('SessionMaster.field.branch_id') ?></th>
                                        <th><?= lang('SessionMaster.field.session_capacity') ?></th>
                                        <th><?= lang('SessionMaster.field.date_start') ?></th>
                                        <th><?= lang('SessionMaster.field.date_end') ?></th>
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
                    url: '<?= base_url('/admin/service/variant/session') ?>',
                    type: 'POST',
                    data: function (data) {}
                }
            });
        });
    </script>
<?php $this->endSection() ?>