<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= ('new' == $mode ? lang('Product.new-product-category') : ($category['category_local_names'][$session->lang] ?? $category['category_name'])) ?></h2>
                    <div class="row">
                        <div class="col col-md-6">
                            <?php
                            echo build_form_input('category_name', lang('ProductCategory.field.category_name'), [
                                'type' => 'text',
                            ], @$category['category_name']);
                            $locales = get_available_locales('long');
                            foreach ($locales as $locale_code => $locale_name) {
                                echo build_form_input('category_local_names_' . $locale_code, lang('ProductCategory.field.category_local_names') . ' (' . $locale_name . ')', [
                                    'type' => 'text',
                                ], @$category['category_local_names'][$locale_code]);
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
<?php $this->endSection() ?>