<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="col-12 col-lg-6">
                        <h2><?= lang('BusinessTag.title') ?></h2>
                        <p><?= lang('BusinessTag.paragraph') ?></p>
                        <div class="mb-3" id="tag-area"></div>
                        <label for="tag-name" class="form-label"><?= lang('BusinessTag.new-business-tag') ?></label>
                        <input type="text" class="form-control" id="tag-name" placeholder="<?= lang('BusinessTag.new-business-tag') ?>"/>
                        <div class="text-end mt-3">
                            <button class="btn btn-primary" id="add-tag"><?= lang('System.buttons.save') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $.post(
                "<?= base_url('admin/business/tag') ?>",
                function (response, status) {
                    if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                        console.log(response.tags);
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
    </script>
<?php $this->endSection() ?>