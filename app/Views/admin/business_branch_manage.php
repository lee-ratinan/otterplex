<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= $branch['branch_local_names'][$lang] ?? lang('Business.branch-management.generic-title') ?></h2>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h3><?= lang('Business.branch-management.generic-information') ?></h3>
                            <?php
                            echo build_form_input('subdivision_code', lang('BranchMaster.field.subdivision_code'), [
                                'type' => 'select',
                            ], @$branch['subdivision_code'], '', $subdivisions);
                            echo build_form_input('branch_name', lang('BranchMaster.field.branch_name'), [
                                'type' => 'text',
                            ], @$branch['branch_name']);
                            echo build_form_input('branch_slug', lang('BranchMaster.field.branch_slug'), [
                                'type'             => 'text',
                                'data-explanation' => lang('BranchMaster.explanation.branch_slug')
                            ], @$branch['branch_slug']);
                            foreach ($all_languages as $lang_code => $language_name) {
                                echo build_form_input('branch_local_names_' . $lang_code, lang('BranchMaster.field.branch_local_names') . ' (' . $language_name . ')', [
                                    'type' => 'text',
                                ], @$branch['branch_local_names'][$lang_code]);
                            }
                            echo build_form_input('timezone_code', lang('BranchMaster.field.timezone_code'), [
                                'type' => 'select'
                            ], @$branch['timezone_code'], '', $timezones);
                            echo build_form_input('branch_type', lang('BranchMaster.field.branch_type'), [
                                'type' => 'select'
                            ], @$branch['branch_type'], '', [
                                'PHYSICAL' => lang('BranchMaster.enum.branch_type.PHYSICAL'),
                                'ONLINE' => lang('BranchMaster.enum.branch_type.ONLINE')
                            ]);
                            echo build_form_input('branch_address', lang('BranchMaster.field.branch_address'), [
                                'type'             => 'text'
                            ], @$branch['branch_address']);
                            echo build_form_input('branch_postal_code', lang('BranchMaster.field.branch_postal_code'), [
                                'type'             => 'text'
                            ], @$branch['branch_postal_code']);
                            echo build_form_input('branch_status', lang('BranchMaster.field.branch_status'), [
                                'type' => 'select'
                            ], @$branch['branch_status'], '', [
                                'ACTIVE'   => lang('BranchMaster.enum.branch_status.ACTIVE'),
                                'INACTIVE' => lang('BranchMaster.enum.branch_status.INACTIVE')
                            ]);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-master"><?= lang('System.buttons.save') ?></button>
                            </div>
                        </div>
                        <?php if ('edit' == $mode) : ?>
                        <div class="col-12 col-md-8">
                            <h3><?= lang('Business.branch-management.opening-hours') ?></h3>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th><?= lang('Business.branch-management.hours.day') ?></th>
                                        <th><?= lang('Business.branch-management.hours.opens') ?></th>
                                        <th><?= lang('Business.branch-management.hours.closes') ?></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($hours as $d => $hour) : ?>
                                        <tr>
                                            <td><?= lang('Business.branch-management.days.' . $d) ?></td>
                                            <td><label><input class="form-control branch-opening-hours-<?= $d ?>" name="branch-opening-hours-<?= $d ?>[]" data-id="<?= $hour[0] ?>" data-day="<?= $d ?>" value="<?= $hour[1] ?>"/></label></td>
                                            <td><label><input class="form-control branch-opening-hours-<?= $d ?>" name="branch-opening-hours-<?= $d ?>[]" data-id="<?= $hour[0] ?>" data-day="<?= $d ?>" value="<?= $hour[2] ?>"/></label></td>
                                            <td class="text-end"><button class="btn btn-primary btn-sm" data-target="branch-opening-hours-<?= $d ?>" id="btn-save-hours"><?= lang('System.buttons.save') ?></button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <h3><?= lang('Business.branch-management.modified-hours') ?></h3>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th><?= lang('BranchModifiedHours.field.modified_hours_date') ?></th>
                                        <th><?= lang('BranchModifiedHours.field.modified_reason') ?></th>
                                        <th><?= lang('BranchModifiedHours.field.modified_type') ?></th>
                                        <th><?= lang('BranchModifiedHours.field.updated_opening_hours') ?></th>
                                        <th><?= lang('BranchModifiedHours.field.updated_closing_hours') ?></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (empty($modified)) : ?>
                                        <tr>
                                            <td colspan="6" class="text-center">-</td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($modified as $row) : ?>
                                            <tr>
                                                <td><?= format_date($row['modified_hours_date']) ?></td>
                                                <td><?= $row['modified_reason'] ?></td>
                                                <td><?= lang('BranchModifiedHours.enum.modified_type.' . $row['modified_type']) ?></td>
                                                <td><?= empty($row['updated_opening_hours']) ? '' : format_time($row['updated_opening_hours']) ?></td>
                                                <td><?= empty($row['updated_closing_hours']) ? '' : format_time($row['updated_closing_hours']) ?></td>
                                                <td class="text-end"><button class="btn btn-primary btn-sm"><?= lang('System.buttons.remove') ?></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <h3><?= lang('Business.branch-management.modified-hours-new') ?></h3>
                            <?php
                            echo build_form_input('modified_hours_date', lang('BranchModifiedHours.field.modified_hours_date'), [
                                'type' => 'date',
                            ], null);
                            echo build_form_input('modified_reason', lang('BranchModifiedHours.field.modified_reason'), [
                                'type' => 'text',
                            ], null);
                            echo build_form_input('modified_type', lang('BranchModifiedHours.field.modified_type'), [
                                'type' => 'select',
                            ], null, '', [
                                'CLOSED'         => lang('BranchModifiedHours.enum.modified_type.CLOSED'),
                                'MODIFIED_HOURS' => lang('BranchModifiedHours.enum.modified_type.MODIFIED_HOURS'),
                            ]);
                            echo build_form_input('updated_opening_hours', lang('BranchModifiedHours.field.updated_opening_hours'), [
                                'type' => 'time',
                            ], null);
                            echo build_form_input('updated_closing_hours', lang('BranchModifiedHours.field.updated_closing_hours'), [
                                'type' => 'time',
                            ], null);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-master"><?= lang('System.buttons.save') ?></button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // ON KEYUP
            $('#branch_name').keyup(function () {
                let branch_name = $(this).val();
                $.post(
                    "<?= base_url('helper/generate-slug') ?>",
                    {name: branch_name},
                    function (response) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            let slug = response.slug;
                            if ('new-branch' === slug) {
                                slug += 'x';
                            }
                            $('#branch_slug').val(slug);
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
            $('#branch_slug').change(function () {
                let slug = $(this).val();
                slug = slug.toLowerCase();
                slug = slug.replace(/[^a-z-]/g, '');
                $(this).val(slug);
            });
        });
    </script>
<?php $this->endSection() ?>