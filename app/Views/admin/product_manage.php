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
                                    echo build_form_input('product_slug', lang('ProductMaster.field.product_slug'), [
                                        'type' => 'text',
                                        'readonly' => 'readonly',
                                        'data-explanation' => lang('System.system-generated')
                                    ], @$product['product_slug']);
                                    $locales = get_available_locales('long');
                                    foreach ($locales as $locale_code => $locale_name) {
                                        echo build_form_input('product_local_names_' . $locale_code, lang('ProductMaster.field.product_local_names') . ' (' . $locale_name . ')', [
                                            'type' => 'text',
                                        ], @$product['product_local_names'][$locale_code]);
                                    }
                                    echo build_form_input('product_tag', lang('ProductMaster.field.product_tag'), [
                                        'type' => 'select'
                                    ], @$product['product_tag'], '', [
                                        'new' => lang('ProductMaster.enum.product_tag.new'),
                                        'popular' => lang('ProductMaster.enum.product_tag.popular'),
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>