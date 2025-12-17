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
                            ], @$variant['variant_name']);
                            echo build_form_input('variant_slug', lang('ProductVariant.field.variant_slug'), [
                                'type'             => 'text',
                                'readonly'         => 'readonly',
                                'data-explanation' => lang('System.system-generated')
                            ], @$variant['variant_slug']);
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
                            ], @$variant['is_active'], '', [
                                'A' => lang('ProductVariant.enum.is_active.A'),
                                'I' => lang('ProductVariant.enum.is_active.I')
                            ]);
                            echo build_form_input('inventory_count', lang('ProductVariant.field.inventory_count'), [
                                'type' => 'number',
                                'min'  => 0
                            ], @$variant['inventory_count']);
                            echo build_form_input('price_active', lang('ProductVariant.field.price_active'), [
                                'type' => 'number',
                                'min'  => 1,
                            ], @$variant['price_active']);
                            echo build_form_input('price_compare', lang('ProductVariant.field.price_compare'), [
                                'type' => 'number',
                                'min'  => 1,
                            ], @$variant['price_compare']);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-master"><?= lang('System.buttons.save') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>