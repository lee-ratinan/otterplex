<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <table class="table table-sm table-hover table-striped">
                        <thead>
                        <tr>
                            <th><?= lang('BranchMaster.field.subdivision_code') ?></th>
                            <th><?= lang('BranchMaster.field.branch_name') ?></th>
                            <th><?= lang('BranchMaster.field.timezone_code') ?></th>
                            <th><?= lang('BranchMaster.field.branch_type') ?></th>
                            <th><?= lang('BranchMaster.field.branch_status') ?></th>
                            <th><?= lang('System.buttons.view-more') ?></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
                <?php if ('th' == $lang) : ?>
                language: {url: '//cdn.datatables.net/plug-ins/2.3.5/i18n/th.json',},
                <?php endif; ?>
                ajax: {
                    url: '<?= base_url('/admin/business/branch') ?>',
                    type: 'POST',
                    data: function (data) {}
                },
                //order: <?php //= isset($table_order) ? $table_order : "[[1, 'desc']]" ?>//,
                // columnDefs: [{orderable: false, targets: 0}],
                drawCallback: function () {
                    // let DateTime = luxon.DateTime;
                    // $('.utc-to-local-time').each(function () {
                    //     const utc = $(this).text();
                    //     if ('' !== utc) {
                    //         $(this).text(DateTime.fromISO(utc).toLocaleString(DateTime.DATETIME_MED));
                    //     } else {
                    //         $(this).text('-');
                    //     }
                    // });
                },
            });
        });
    </script>
<?php $this->endSection() ?>