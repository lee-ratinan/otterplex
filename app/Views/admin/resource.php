<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-end">
                        <a class="btn btn-primary" href="<?= base_url('admin/resource/0') ?>">
                            <i class="fa-solid fa-circle-plus"></i> <?= lang('Business.resource-management.new-resource') ?>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('ResourceMaster.field.resource_name') ?></th>
                                <th><?= lang('ResourceMaster.field.resource_description') ?></th>
                                <th><?= lang('ResourceMaster.field.branch_id') ?></th>
                                <th><?= lang('ResourceMaster.field.resource_type_id') ?></th>
                                <th><?= lang('ResourceMaster.field.is_active') ?></th>
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
                    url: '<?= base_url('/admin/resource') ?>',
                    type: 'POST',
                    data: function (data) {}
                }
            });
        });
    </script>
<?php $this->endSection() ?>