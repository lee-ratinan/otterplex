<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h2><i class="bi bi-shop-window"></i> <?= lang('Admin.my-businesses.title') ?></h2>
                    <table class="table table-sm table-hover table-striped">
                        <thead>
                        <tr>
                            <th><?= lang('BusinessMaster.field.business_name') ?></th>
                            <th><?= lang('BusinessUser.field.user_role') ?></th>
                            <th><?= lang('BusinessUser.field.role_status') ?></th>
                            <th><?= lang('BusinessUser.field.my_default_business') ?></th>
                            <th><?= lang('BusinessMaster.field.business_status') ?></th>
                            <th><?= lang('BusinessMaster.field.contract_expiry') ?></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($myBusinesses as $business) : ?>
                            <?php
                            // calculation
                            $business_status = 'E';
                            if ($business['contract_expiry'] >= date(DATE_FORMAT_DB)) {
                                $business_status = 'A';
                            }
                            ?>
                            <tr>
                                <td><?= $business['business_local_names'][$lang] ?? $business['business_name'] ?></td>
                                <td><?= lang('BusinessUser.enum.user_role.' . $business['user_role']) ?></td>
                                <td><?= lang('BusinessUser.enum.role_status.' . $business['role_status']) ?></td>
                                <td><?= lang('BusinessUser.enum.my_default_business.' . $business['my_default_business']) ?></td>
                                <td><?= lang('BusinessMaster.enum.business_status.' . $business_status) ?></td>
                                <td><?= format_date($business['contract_expiry']) ?></td>
                                <td>
                                    <?php if ('A' == $business_status && 'ACTIVE' == $business['role_status']) : ?>
                                        <?php if ($session->business['business_slug'] == $business['business_slug']) : ?>
                                            <?= lang('Admin.my-businesses.you-are-here') ?>
                                        <?php else : ?>
                                            <a href="#" data-target="<?= $business['business_slug'] ?>" class="btn btn-sm btn-outline-primary btn-switch-biz"><?= lang('Admin.my-businesses.btn.switch-to') ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ('OWNER' == $business['user_role'] && $business['business_id'] == $session->business['id']) : ?>
                                        <a href="<?= base_url('admin/business') ?>" class="btn btn-sm btn-outline-primary"><?= lang('Admin.my-businesses.btn.manage') ?></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <pre>
                        use cases:
                        - add business here
                    </pre>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('.btn-switch-biz').click(function (e) {
                e.preventDefault();
                let target_business_slug = $(this).data('target');
                $.post(
                    "<?= base_url('admin/switch-business') ?>",
                    {target_business_slug: target_business_slug},
                    function(response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
        });
    </script>
<?php $this->endSection() ?>