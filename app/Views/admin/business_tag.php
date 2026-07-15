<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="col-12 col-lg-6">
                        <h2><?= lang('BusinessTag.title') ?> (<span id="tag-count"></span>)</h2>
                        <p><?= lang('BusinessTag.paragraph', [BUSINESS_TAG_MAX]) ?></p>
                        <div class="mb-3" id="tag-area"></div>
                        <div id="new-tag-form">
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
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function renderTags(tags, count) {
                $('#tag-area').empty();
                $('#tag-count').html(count);
                if (0 === count) {
                    $('#tag-area').append('<div class="alert alert-warning"><i class="fa-solid fa-triangle-exclamation"></i> <?= lang('System.generic-term.no-data') ?></div>');
                } else {
                    $.each(tags, function (i, tag) {
                        $('#tag-area').append('<div class="mb-1">' + tag.tag_name + ' <button class="btn btn-outline-danger btn-sm btn-delete" data-business-id="' + tag.business_id + '" data-tag-id="' + tag.tag_id + '"><i class="fa-solid fa-times-circle"></i></button></div>')
                    });
                }
                if (count < <?= BUSINESS_TAG_MAX ?>) {
                    $('#new-tag-form').show();
                } else {
                    $('#new-tag-form').hide();
                }
            }
            // on load
            function retrieveTags() {
                $.post(
                    "<?= base_url('admin/business/tag') ?>",
                    function (response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            renderTags(response.tags, response.count);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            }
            retrieveTags();
            // on insert
            $('#add-tag').click(function (e) {
                e.preventDefault();
                let tag = $('#tag-name').val();
                if ('' === tag) {
                    $('#tag-name').focus();
                    return false;
                }
                $.post(
                    "<?= base_url('admin/business/tag/save') ?>",
                    {
                        tag_name: tag
                    },
                    function (response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            retrieveTags();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                        $('#tag-name').val('');
                    },
                    "json"
                ).fail(function (response) {
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            // on delete
            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let tag_id = $(this).data('tag-id');
                $.post(
                    "<?= base_url('admin/business/tag/delete') ?>",
                    {
                        tag_id: tag_id
                    },
                    function (response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            retrieveTags();
                            toastr.success(response.message);
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