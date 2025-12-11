<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-end">
                        <a class="btn btn-primary" href="<?= base_url('admin/business/branch/new-branch') ?>">
                            <i class="bi bi-plus-circle"></i>
                            <?= lang('Business.branch-management.new-branch') ?>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('BranchMaster.field.subdivision_code') ?></th>
                                <th><?= lang('BranchMaster.field.branch_name') ?></th>
                                <th><?= lang('BranchMaster.field.timezone_code') ?></th>
                                <th><?= lang('BranchMaster.field.branch_type') ?></th>
                                <th><?= lang('BranchMaster.field.branch_status') ?></th>
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
                ordering: false,
                <?php if ('en' != $lang) : ?>
                language: {url: '<?= base_url('/assets/vendor/DataTables/i18n/' . $lang . '.json') ?>',},
                <?php endif; ?>
                ajax: {
                    url: '<?= base_url('/admin/business/branch') ?>',
                    type: 'POST',
                    data: function (data) {}
                }
            });
        });
    </script>
<?php $this->endSection() ?>