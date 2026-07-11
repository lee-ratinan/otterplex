<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css" rel="stylesheet">
    <style>
        [data-bs-theme="dark"] {
            .ql-snow .ql-stroke {fill: none;stroke: #fff;stroke-linecap: round;stroke-linejoin: round;stroke-width: 2;}
            .ql-snow .ql-fill, .ql-snow .ql-stroke.ql-fill {stroke: #eee !important;fill: #eee !important;}
            .ql-editor.ql-blank::before {color: #eee;}
        }
        #policy_text, .ql-editor { min-height: 200px; }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= lang('Admin.pages.business-policy') . ': ' . lang('BusinessPolicy.types.' . $policy_type) . ' (' . $languages[$language_code] . ')' ?></h2>
                    <input type="hidden" id="policy_id" value="<?= $id ?>" />
                    <input type="hidden" id="business_id" value="<?= $business_id ?>" />
                    <input type="hidden" id="language_code" value="<?= $language_code ?>" />
                    <input type="hidden" id="policy_type" value="<?= $policy_type ?>" />
                    <div class="mb-3">
                        <div id="toolbar-container">
                            <span class="ql-formats">
                                <button class="ql-bold"></button>
                                <button class="ql-italic"></button>
                                <button class="ql-underline"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-header" value="2"></button>
                                <button class="ql-header" value="3"></button>
                                <button class="ql-header" value="4"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-list" value="ordered"></button>
                                <button class="ql-list" value="bullet"></button>
                                <button class="ql-indent" value="-1"></button>
                                <button class="ql-indent" value="+1"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-direction" value="rtl"></button>
                                <select class="ql-align"></select>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-link"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-clean"></button>
                            </span>
                        </div>
                        <div id="policy_text"></div>
                    </div>
                    <div class="text-end">
                        <button id="btn-save" class="btn btn-primary"><?= lang('System.buttons.save') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const quill = new Quill('#policy_text', {
                modules: {
                    syntax: true,
                    toolbar: '#toolbar-container',
                },
                theme: 'snow',
                placeholder: '<?= lang('BusinessPolicy.types.' . $policy_type) . ' (' . $languages[$language_code] . ')' ?>',
            });
            <?php if (isset($policy['policy_text_delta'])) : ?>
                <?php $delta = json_decode($policy['policy_text_delta']); ?>
                quill.setContents(<?= json_encode($delta) ?>);
            <?php endif; ?>
            $('#btn-save').click(function (e) {
                e.preventDefault();
                // 1. Extract content as semantic HTML
                const htmlContent = quill.getSemanticHTML();
                // Optional: If you also want to save the Delta Array JSON string for future re-editing
                const deltaContent = JSON.stringify(quill.getContents());
                $('#btn-save').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/business/policy/manage') ?>",
                    {
                        policy_id: $('#policy_id').val(),
                        business_id: $('#business_id').val(),
                        language_code: $('#language_code').val(),
                        policy_type: $('#policy_type').val(),
                        policy_text_html: htmlContent,
                        policy_text_delta: deltaContent
                    },
                    function (response, status) {
                        $('#btn-save').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.href='<?= base_url('admin/business/policy') ?>'; }, 3000);
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