<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= $resource['resource_name'] ?? lang('Business.resource-management.new-resource') ?></h2>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <?php
                            echo build_form_input('branch_id', lang('ResourceMaster.field.branch_id'), [
                                'type' => 'select',
                            ], @$resource['branch_id'], '', $branches);
                            echo build_form_input('resource_type_id', lang('ResourceMaster.field.resource_type_id'), [
                                'type' => 'select',
                            ], @$resource['resource_type_id'], '', $types);
                            echo build_form_input('resource_name', lang('ResourceMaster.field.resource_name'), [
                                'type' => 'text',
                            ], @$resource['resource_name']);
                            echo build_form_input('resource_description', lang('ResourceMaster.field.resource_description'), [
                                'type' => 'text',
                            ], @$resource['resource_description']);
                            echo build_form_input('is_active', lang('ResourceMaster.field.is_active'), [
                                'type' => 'select',
                            ], @$resource['is_active'], '', [
                                'A' => lang('ResourceMaster.enum.is_active.A'),
                                'I' => lang('ResourceMaster.enum.is_active.I')
                            ]);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <input type="hidden" id="id" name="id" value="<?= $resource['id'] ?? 0 ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('#btn-save').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['branch_id', 'resource_type_id', 'resource_name', 'resource_description', 'is_active'];
                gen_js_fields_checker($fields);
                ?>
                $('#btn-save').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/resource/manage') ?>",
                    <?php $fields[] = 'id'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.href='<?= base_url('admin/resource') ?>'; }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-save').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
        });
    </script>
<?php $this->endSection() ?>