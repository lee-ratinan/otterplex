<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="text-end">
                        <a class="btn btn-primary" href="<?= base_url('admin/business/user/0') ?>">
                            <i class="bi bi-plus-circle"></i>
                            <?= lang('Business.user-management.new-user') ?>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('UserMaster.field.user_full_name') ?></th>
                                <th><?= lang('UserMaster.field.email_address') ?></th>
                                <th><?= lang('UserMaster.field.account_status') ?></th>
                                <th><?= lang('BusinessUser.field.user_role') ?></th>
                                <th><?= lang('BusinessUser.field.role_status') ?></th>
                                <th><?= lang('BranchMaster.field.branch_name') ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <pre>
                        use cases:
                        user_master (add new user who is just a staff/manager > link to business_user)
                        business_user (link, unlink)
                        branch_user (link, unlink, change branch, whatever)
                    </pre>
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
                    url: '<?= base_url('/admin/business/user') ?>',
                    type: 'POST',
                    data: function (data) {}
                }
            });
        });
    </script>
<?php $this->endSection() ?>