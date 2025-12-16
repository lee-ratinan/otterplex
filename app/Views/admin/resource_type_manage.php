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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

        });
    </script>
<?php $this->endSection() ?>