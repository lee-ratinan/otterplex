<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h2>
                        <?php if ('new' == $mode) : ?>
                            <?= lang('Product.new-product-variant') ?>
                        <?php else : ?>
                            <?= $variant['product_local_names'][$session->lang] ?? $variant['product_name'] ?> /
                            <?= $variant['variant_local_names'][$session->lang] ?? $variant['variant_name'] ?>
                        <?php endif; ?>
                    </h2>
                    <div class="row">
                        <div class="col col-md-6">
                            <?php
                            echo build_form_input('variant_name', lang('ProductVariant.field.variant_name'), [
                                'type' => 'text',
                            ], $variant['variant_name'] ?? lang('ProductVariant.default.variant_name'));
                            echo build_form_input('variant_sku', lang('ProductVariant.field.variant_sku'), [
                                'type' => 'text',
                            ], @$variant['variant_sku']);
                            $locales = get_available_locales('long');
                            foreach ($locales as $locale_code => $locale_name) {
                                echo build_form_input('variant_local_names_' . $locale_code, lang('ProductVariant.field.variant_local_names') . ' (' . $locale_name . ')', [
                                    'type' => 'text',
                                ], @$variant['variant_local_names'][$locale_code]);
                            }
                            echo build_form_input('is_active', lang('ProductVariant.field.is_active'), [
                                'type' => 'select',
                            ], $variant['is_active'] ?? 'A', '', [
                                'A' => lang('ProductVariant.enum.is_active.A'),
                                'I' => lang('ProductVariant.enum.is_active.I')
                            ]);
                            echo build_form_input('inventory_count', lang('ProductVariant.field.inventory_count'), [
                                'type' => 'number',
                                'min'  => 0
                            ], $variant['inventory_count'] ?? '0');
                            echo build_form_input('price_active', lang('ProductVariant.field.price_active'), [
                                'type' => 'number',
                                'min'  => 1,
                            ], $variant['price_active'] ?? '1');
                            echo build_form_input('price_compare', lang('ProductVariant.field.price_compare'), [
                                'type' => 'number',
                                'min'  => 1,
                            ], $variant['price_compare'] ?? '1');
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-variant"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <input type="hidden" name="variant_id" id="variant_id" value="<?= $variant['id'] ?? 0 ?>" />
                            <input type="hidden" name="product_id" id="product_id" value="<?= $productId ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // SAVE
            $('#btn-save-variant').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['variant_name', 'is_active', 'inventory_count', 'price_active', 'price_compare'];
                foreach ($locales as $code => $language_name) {
                    $fields[] = 'variant_local_names_' . $code;
                }
                gen_js_fields_checker($fields);
                ?>
                $('#btn-save-variant').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/product/variant/manage') ?>",
                    <?php $fields[] = 'variant_sku'; $fields[] = 'product_id'; $fields[] = 'variant_id'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save-variant').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            let id = response.id * <?= ID_MASKED_PRIME ?>;
                            setTimeout(function() { location.href='<?= base_url('admin/product/variant/' . $pIdPrime . '/') ?>' + id; }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-save-variant').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
        });
    </script>
<?php $this->endSection() ?>