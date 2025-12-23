<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= $resourceType['resource_local_names'][$session->lang] ?? lang('Business.resource-management.new-resource-type') ?></h2>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <?php
                            echo build_form_input('resource_type', lang('ResourceType.field.resource_type'), [
                                'type' => 'text',
                            ], @$resourceType['resource_type']);
                            $languages = get_available_locales('long');
                            foreach ($languages as $code => $language_name) {
                                echo build_form_input('resource_local_names_' . $code, lang('ResourceType.field.resource_local_names') . ' (' . $language_name . ')', [
                                    'type' => 'text',
                                ], @$resourceType['resource_local_names'][$code]);
                            }
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <input type="hidden" id="id" name="id" value="<?= $resourceType['id'] ?? 0 ?>" />
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
                $fields = ['resource_type'];
                foreach ($languages as $code => $language_name) {
                    $fields[] = 'resource_local_names_' . $code;
                }
                gen_js_fields_checker($fields);
                ?>
                $('#btn-save').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/resource/type-manage') ?>",
                    <?php $fields[] = 'id'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.href='<?= base_url('admin/resource/type') ?>'; }, 3000);
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