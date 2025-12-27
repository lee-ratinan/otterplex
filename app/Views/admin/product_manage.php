<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col">
                            <h2><?= 'new' == $mode ? lang('Product.new-product') : ($product['product_local_names'][$session->lang] ?? $product['product_name']) ?></h2>
                            <div class="row">
                                <div class="col col-md-6">
                                    <?php
                                    echo build_form_input('product_category_id', lang('ProductMaster.field.product_category_id'), [
                                        'type' => 'select'
                                    ], @$product['product_category_id'], '', $categories);
                                    echo build_form_input('product_name', lang('ProductMaster.field.product_name'), [
                                        'type' => 'text'
                                    ], @$product['product_name']);
                                    $locales = get_available_locales('long');
                                    foreach ($locales as $locale_code => $locale_name) {
                                        echo build_form_input('product_local_names_' . $locale_code, lang('ProductMaster.field.product_local_names') . ' (' . $locale_name . ')', [
                                            'type' => 'text',
                                        ], @$product['product_local_names'][$locale_code]);
                                    }
                                    foreach ($locales as $locale_code => $locale_name) {
                                        echo build_form_input('product_description_' . $locale_code, lang('ProductMaster.field.product_description') . ' (' . $locale_name . ')', [
                                            'type' => 'text',
                                        ], @$product['product_description'][$locale_code]);
                                    }
                                    echo build_form_input('product_tag', lang('ProductMaster.field.product_tag'), [
                                        'type' => 'select'
                                    ], @$product['product_tag'], '', [
                                        'none'        => '-',
                                        'new'         => lang('ProductMaster.enum.product_tag.new'),
                                        'popular'     => lang('ProductMaster.enum.product_tag.popular'),
                                        'recommended' => lang('ProductMaster.enum.product_tag.recommended'),
                                    ]);
                                    echo build_form_input('product_type', lang('ProductMaster.field.product_type'), [
                                        'type' => 'select'
                                    ], @$product['product_type'], '', [
                                        'P' => lang('ProductMaster.enum.product_type.P'),
                                        'D' => lang('ProductMaster.enum.product_type.D'),
                                    ]);
                                    echo build_form_input('is_active', lang('ProductMaster.field.is_active'), [
                                        'type' => 'select',
                                    ], @$product['is_active'], '', [
                                        'A' => lang('ProductMaster.enum.is_active.A'),
                                        'I' => lang('ProductMaster.enum.is_active.I'),
                                    ]);
                                    ?>
                                    <div class="text-end">
                                        <button class="btn btn-primary" id="btn-save-master"><?= lang('System.buttons.save') ?></button>
                                    </div>
                                    <input type="hidden" name="product_id" id="product_id" value="<?= $product['id'] ?? 0 ?>" />
                                </div>
                            </div>
                            <?php if ('edit' == $mode) : ?>
                                <!-- UPLOAD PRODUCT IMAGE -->
                                <hr />
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <h3><?= lang('Product.upload-image') ?></h3>
                                        <form id="form-upload-image" action="<?= base_url('/admin/product/manage') ?>" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="script_action" value="upload_image"/>
                                            <input type="file" id="product_image" name="product_image" class="form-control my-3"/>
                                            <input type="hidden" id="slug_for_image" name="slug_for_image" value="<?= $product['product_slug'] ?>"/>
                                            <input type="hidden" id="id_for_image" name="id_for_image" value="<?= $product['id'] ?>"/>
                                            <p class="small"><?= lang('Product.upload-explanation') ?></p>
                                            <div class="text-end">
                                                <button id="btn-upload-image" type="submit" class="btn btn-primary"><?= lang('System.buttons.upload') ?></button>
                                                <button id="btn-remove-image" type="button" class="btn btn-outline-danger"><?= lang('System.buttons.remove') ?></button>
                                                <button id="btn-remove-image-confirm" type="button" class="btn btn-outline-danger" style="display:none"><?= lang('System.buttons.remove-confirm') ?></button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php if (!empty($product['product_image'])) : ?>
                                            <img src="<?= base_url('file/' . $product['product_image']) ?>" class="img-fluid" />
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr />
                                <h2><?= lang('Product.product-variant') ?></h2>
                                <div class="text-end">
                                    <a class="btn btn-primary" href="<?= base_url('admin/product/variant/' . ($product['id'] * ID_MASKED_PRIME) . '/0') ?>"><?= lang('Product.new-product-variant') ?></a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th><?= lang('ProductVariant.field.variant_sku') ?></th>
                                            <th><?= lang('ProductVariant.field.variant_name') ?></th>
                                            <th><?= lang('ProductVariant.field.is_active') ?></th>
                                            <th><?= lang('ProductVariant.field.inventory_count') ?></th>
                                            <th><?= lang('ProductVariant.field.price_active') ?></th>
                                            <th><?= lang('ProductVariant.field.price_compare') ?></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($variants as $variant) : ?>
                                        <tr>
                                            <td><?= $variant['variant_sku'] ?></td>
                                            <td><?= $variant['variant_local_names'][$session->lang] ?? $variant['variant_name'] ?></td>
                                            <td><?= lang('ProductVariant.enum.is_active.' . $variant['is_active']) ?></td>
                                            <td><?= number_format($variant['inventory_count']) ?></td>
                                            <td><?= format_price($variant['price_active'], $session->business['currency_code']) ?></td>
                                            <td><?= format_price($variant['price_compare'], $session->business['currency_code']) ?></td>
                                            <td class="text-end">
                                                <a class="btn btn-primary btn-sm mb-1" href="<?= base_url('admin/product/variant/inventory/' . ($product['id'] * ID_MASKED_PRIME) . '/' . ($variant['id'] * ID_MASKED_PRIME)) ?>"><i class="fa-solid fa-warehouse"></i> <?= lang('Product.inventory') ?></a>
                                                <a class="btn btn-primary btn-sm mb-1 ms-1" href="<?= base_url('admin/product/variant/' . ($product['id'] * ID_MASKED_PRIME) . '/' . ($variant['id'] * ID_MASKED_PRIME)) ?>"><?= lang('System.buttons.edit') ?></a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = $('table').DataTable();
            // SAVE
            $('#btn-save-master').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['product_category_id', 'product_name', 'product_tag', 'product_type', 'is_active'];
                foreach ($locales as $code => $language_name) {
                    $fields[] = 'product_local_names_' . $code;
                }
                gen_js_fields_checker($fields);
                foreach ($locales as $code => $language_name) {
                    $fields[] = 'product_description_' . $code;
                }
                ?>
                $('#btn-save-master').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/product/manage') ?>",
                    <?php $fields[] = 'product_id'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save-master').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            let id = response.id * <?= ID_MASKED_PRIME ?>;
                            setTimeout(function() { location.href='<?= base_url('admin/product/') ?>' + id; }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-save-master').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            // IMAGE
            $('#btn-upload-image').on('click', function (e) {
                e.preventDefault();
                // check if the file is selected
                if ($('#product_image').val() === '') {
                    toastr.warning('<?= lang('System.response-msg.error.please-check-empty-field') ?>');
                    $('#product_image').focus();
                    return;
                }
                $('#btn-upload-image').prop('disabled', true);
                // submit #form-upload-avatar form in AJAX
                $.ajax({
                    url: '<?= base_url('/admin/product/manage') ?>',
                    type: 'POST',
                    data: new FormData($('#form-upload-image')[0]),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        $('#btn-upload-image').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#btn-upload-image').prop('disabled', false);
                        let response = JSON.parse(xhr.responseText);
                        let message = response.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                        toastr.error(message);
                    }
                });
            });
            $('#btn-remove-image').on('click', function (e) {
                e.preventDefault();
                $('#btn-remove-image').hide();
                $('#btn-remove-image-confirm').show();
            });
            $('#btn-remove-image-confirm').on('click', function (e) {
                e.preventDefault();
                $('#btn-remove-image-confirm').prop('disabled', true);
                $.ajax({
                    url: '<?= base_url('/admin/product/manage') ?>',
                    type: 'POST',
                    data: {
                        script_action: 'remove_image',
                        product_image: '<?= @$product['product_image'] ?>',
                        id_for_image: '<?= @$product['id'] ?>'
                    },
                    success: function (response) {
                        $('#btn-remove-image-confirm').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#btn-remove-image-confirm').prop('disabled', false);
                        let response = JSON.parse(xhr.responseText);
                        let message = response.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                        toastr.error(message);
                    }
                });
            });
        });
    </script>
<?php $this->endSection() ?>