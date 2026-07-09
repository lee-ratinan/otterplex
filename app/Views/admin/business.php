<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= lang('Business.title', [$business['business_local_names'][$lang] ?? $business['business_name']]) ?></h2>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="row">
                                <div class="col-6 col-sm-4">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <h6><?= lang('BusinessMaster.field.country_code') ?></h6>
                                            <?= get_country_name_single_language($business['country_code'], $session->lang) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <h6><?= lang('BusinessMaster.field.currency_code') ?></h6>
                                            <?= get_currency_common_name($business['currency_code'], $session->lang) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <h6><?= lang('BusinessMaster.field.contract_plan') ?></h6>
                                            <?= lang('BusinessMaster.enum.contract_plan.' . $business['contract_plan']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <h6><?= lang('BusinessMaster.field.contract_expiry') ?></h6>
                                            <?= format_date($business['contract_expiry'], $session->lang) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <h6><?= lang('BusinessMaster.field.review_stars') ?></h6>
                                            <b><?= number_format($business['review_stars'], 1) ?></b><small>/<?= number_format($business['review_count']) ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <h6><?= lang('BusinessMaster.field.live_status') ?></h6>
                                            <?= ('Y' == $business['live_status']) ? '<i class="fa-solid fa-toggle-on text-success"></i>' : '<i class="fa-solid fa-toggle-off text-danger"></i>' ?>
                                            <?= lang('BusinessMaster.enum.live_status.' . $business['live_status']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card border-1 rounded">
                                <div class="card-body p-3">
                                    <ul class="mb-0">
                                        <li><a href="#generic-information"><?= lang('Business.subtitle.generic-information') ?></a></li>
                                        <li><a href="#tax-information"><?= lang('Business.subtitle.tax-information') ?></a></li>
                                        <li><a href="#contact"><?= lang('BusinessMaster.field.contact') ?></a></li>
                                        <li><a href="#social-media"><?= lang('BusinessMaster.field.social_media') ?></a></li>
                                        <li><a href="#shipping"><?= lang('BusinessMaster.field.shipping') ?></a></li>
                                        <li><a href="#seo"><?= lang('Business.subtitle.mart-seo') ?></a></li>
                                        <li><a href="#decoration"><?= lang('Business.subtitle.mart-decoration') ?></a></li>
                                        <li><a href="#upload-your-logo"><?= lang('Business.upload-logo') ?></a></li>
                                        <li><a href="#upload-your-header-img"><?= lang('Business.upload-header-img') ?></a></li>
                                        <li><a href="#clear-cache-header"><?= lang('Business.btn-clear-cache') ?></a></li>
                                    </ul>
                                </div>
                            </div>
                            <h3 class="mt-5 pt-5" id="generic-information"><?= lang('Business.subtitle.generic-information') ?></h3>
                            <?php
                            echo build_form_input('business_type_id', lang('BusinessMaster.field.business_type_id'), [
                                'type' => 'select',
                            ], $business['business_type_id'], '', $business_types);
                            echo build_form_input('business_name', lang('BusinessMaster.field.business_name'), [
                                'type' => 'text',
                                'data-explanation' => lang('BusinessMaster.explanation.business_name'),
                            ], $business['business_name']);
                            echo '<button class="btn btn-outline-danger btn-sm mb-3" id="btn-update-slug">' . lang('Business.btn-update-slug') . '</button>';
                            echo '<button class="btn btn-danger btn-sm mb-3 d-none" id="btn-update-slug-confirm">' . lang('Business.btn-update-slug-confirm') . '</button>';
                            echo build_form_input('business_slug', lang('BusinessMaster.field.business_slug'), [
                                'type'             => 'text',
                                'readonly'         => 'readonly'
                            ], $business['business_slug']);
                            $marketplace_url = getenv('marketplace_site') . '@' . $business['business_slug'];
                            echo '<div class="alert alert-info mb-3"><i class="fa-solid fa-info-circle"></i> ' . lang('Business.marketplace-url', [$marketplace_url]) . '</div>';
                            foreach ($all_languages as $lang_code => $language_name) {
                                echo build_form_input('business_local_names_' . $lang_code, lang('BusinessMaster.field.business_local_names') . ' (' . $language_name . ')', [
                                    'type' => 'text'
                                ], @$business['business_local_names'][$lang_code]);
                            }
                            echo build_form_input('allow_advance_booking', lang('BusinessMaster.field.allow_advance_booking'), [
                                'type' => 'number'
                            ], $business['allow_advance_booking']);
                            echo build_form_input('live_status', lang('BusinessMaster.field.live_status'), [
                                'type'             => 'select',
                                'data-explanation' => lang('BusinessMaster.explanation.live_status')
                            ], $business['live_status'], '', [
                                'Y' => lang('BusinessMaster.enum.live_status.Y'),
                                'N' => lang('BusinessMaster.enum.live_status.N'),
                            ]);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-1"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <h3 class="mt-5 pt-5" id="tax-information"><?= lang('Business.subtitle.tax-information') ?></h3>
                            <?php
                            // country code is not updatable
                            echo build_form_input('tax_percentage', lang('BusinessMaster.field.tax_percentage'), [
                                'type' => 'number',
                                'min'  => 0,
                                'max'  => 100
                            ], $business['tax_percentage']);
                            echo build_form_input('tax_inclusive', lang('BusinessMaster.field.tax_inclusive'), [
                                'type'             => 'select',
                                'data-explanation' => lang('BusinessMaster.explanation.tax_inclusive')
                            ], $business['tax_inclusive'], '', [
                                'I' => lang('BusinessMaster.enum.tax_inclusive.I'),
                                'E' => lang('BusinessMaster.enum.tax_inclusive.E'),
                                'X' => lang('BusinessMaster.enum.tax_inclusive.X')
                            ]);
                            $currencies_for_this_country = get_available_currency_code_by_country($business['country_code']);
                            $currency_list               = [];
                            if ('Y' == $business['live_status']) {
                                $currency_list[$business['currency_code']] = get_currency_common_name($business['currency_code'], $session->lang);
                                echo build_form_input('currency_code', lang('BusinessMaster.field.currency_code'), [
                                    'type'             => 'select',
                                    'data-explanation' => lang('BusinessMaster.explanation.currency_code')
                                ], $business['currency_code'], '', $currency_list);
                            } else {
                                foreach ($currencies_for_this_country as $cd) {
                                    $currency_list[$cd] = get_currency_common_name($cd, $session->lang);
                                }
                                echo build_form_input('currency_code', lang('BusinessMaster.field.currency_code'), [
                                    'type'             => 'select',
                                    'data-explanation' => lang('BusinessMaster.explanation.currency_code')
                                ], $business['currency_code'], '', $currency_list);
                            }
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-2"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <h3 class="mt-5 pt-5" id="contact"><?= lang('BusinessMaster.field.contact') ?></h3>
                            <?php
                            echo build_form_input('contact_email_address', lang('BusinessMaster.field.contact_email_address'), [
                                'type'      => 'email',
                                'maxlength' => 64
                            ], $business['contact_email_address']);
                            echo build_form_input('contact_phone_number', lang('BusinessMaster.field.contact_phone_number'), [
                                'type'      => 'tel',
                                'maxlength' => 24
                            ], $business['contact_phone_number']);
                            echo build_form_input('contact_website', lang('BusinessMaster.field.contact_website'), [
                                'type'      => 'url',
                                'maxlength' => 36
                            ], $business['contact_website']);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-3"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <h3 class="mt-5 pt-5" id="social-media"><?= lang('BusinessMaster.field.social_media') ?></h3>
                            <?php
                            $social_medias = get_social_media();
                            foreach ($social_medias as $code => $social_name) {
                                echo build_form_input('social_media_' . $code, '<i class="fa-brands fa-' . strtolower($social_name) . '"></i> ' . $social_name, [
                                    'type' => 'url',
                                ], @$business['social_media'][$code]);
                            }
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-4"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <h3 class="mt-5 pt-5" id="shipping"><?= lang('BusinessMaster.field.shipping') ?></h3>
                            <?php
                            echo build_form_input('shipping_options', lang('BusinessMaster.field.shipping_options'), [
                                'type' => 'select',
                            ], $business['shipping_options'], '', [
                                'SHIPPING'        => lang('BusinessMaster.enum.shipping_options.SHIPPING'),
                                'SELF-COLLECTION' => lang('BusinessMaster.enum.shipping_options.SELF-COLLECTION'),
                                'BOTH'            => lang('BusinessMaster.enum.shipping_options.BOTH'),
                            ]);
                            echo build_form_input('shipping_fee_taxable', lang('BusinessMaster.field.shipping_fee_taxable'), [
                                'type' => 'select',
                                'data-explanation' => lang('BusinessMaster.explanation.shipping_fee_taxable', [base_url('admin/shipping-fee')])
                            ], $business['shipping_fee_taxable'], '', [
                                'Y' => lang('BusinessMaster.enum.shipping_fee_taxable.Y'),
                                'N' => lang('BusinessMaster.enum.shipping_fee_taxable.N'),
                            ]);
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-3"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <h3 class="mt-5 pt-5" id="seo"><?= lang('Business.subtitle.mart-seo') ?></h3>
                            <?php
                            foreach ($all_languages as $lang_code => $language_name) {
                                echo '<p><b>' . $language_name . '</b></p>';
                                echo build_form_input('mart_meta_description_' . $lang_code, lang('BusinessMaster.field.mart_meta_description'), [
                                    'type' => 'text',
                                ], @$business['mart_meta_description'][$lang_code]);
                                echo build_form_input('mart_meta_keywords_' . $lang_code, lang('BusinessMaster.field.mart_meta_keywords'), [
                                    'type' => 'text',
                                ], @$business['mart_meta_keywords'][$lang_code]);
                            }
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-5"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <h3 class="mt-5 pt-5" id="decoration"><?= lang('Business.subtitle.mart-decoration') ?></h3>
                            <?php
                            echo build_form_input('mart_primary_color', lang('BusinessMaster.field.mart_primary_color'), [
                                'type' => 'color',
                            ], '#' . $business['mart_primary_color'], 'mart-reset-color');
                            echo build_form_input('mart_text_color', lang('BusinessMaster.field.mart_text_color'), [
                                'type' => 'color',
                            ], '#' . $business['mart_text_color'], 'mart-reset-color');
                            echo build_form_input('mart_background_color', lang('BusinessMaster.field.mart_background_color'), [
                                'type' => 'color',
                            ], '#' . $business['mart_background_color'], 'mart-reset-color');
                            foreach ($all_languages as $lang_code => $language_name) {
                                echo build_form_input('mart_store_intro_paragraph_' . $lang_code, lang('BusinessMaster.field.mart_store_intro_paragraph') . ' (' . $language_name . ')', [
                                    'type' => 'textarea',
                                ], @$business['mart_store_intro_paragraph'][$lang_code]);
                            }
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-6"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <!-- UPLOAD LOGO -->
                            <h3 class="mt-5 pt-5" id="upload-your-logo"><?= lang('Business.upload-logo') ?></h3>
                            <form id="form-upload-logo" action="<?= base_url('/admin/business') ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="script_action" value="upload_logo"/>
                                <input type="file" id="logo" name="logo" class="form-control my-3"/>
                                <p class="small"><?= lang('Business.upload-explanation') ?></p>
                                <div class="text-end">
                                    <button id="btn-upload-logo" type="submit" class="btn btn-primary"><?= lang('System.buttons.upload') ?></button>
                                    <button id="btn-remove-logo" type="button" class="btn btn-outline-danger"><?= lang('System.buttons.remove') ?></button>
                                    <button id="btn-remove-logo-confirm" type="button" class="btn btn-outline-danger" style="display:none"><?= lang('System.buttons.remove-confirm') ?></button>
                                </div>
                            </form>
                            <!-- UPLOAD HEADER IMG -->
                            <h3 class="mt-5 pt-5" id="upload-your-header-img"><?= lang('Business.upload-header-img') ?></h3>
                            <form id="form-upload-header-img" action="<?= base_url('/admin/business') ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="script_action" value="upload_header_img"/>
                                <input type="file" id="header-img" name="header-img" class="form-control my-3"/>
                                <p class="small"><?= lang('Business.upload-explanation-header') ?></p>
                                <div class="text-end">
                                    <button id="btn-upload-header-img" type="submit" class="btn btn-primary"><?= lang('System.buttons.upload') ?></button>
                                    <button id="btn-remove-header-img" type="button" class="btn btn-outline-danger"><?= lang('System.buttons.remove') ?></button>
                                    <button id="btn-remove-header-img-confirm" type="button" class="btn btn-outline-danger" style="display:none"><?= lang('System.buttons.remove-confirm') ?></button>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col m-3 px-0" id="example-mart-background">
                                    <?php if (!empty($business['business_header'])) : ?>
                                        <img class="img mb-3 w-100" id="example-mart-logo" src="<?= base_url('file/business_' . $business['business_header']) ?>" alt="<?= $business['business_local_names'][$lang] ?>" />
                                    <?php endif; ?>
                                    <div class="p-3">
                                        <?php if (!empty($logo_file)) : ?>
                                            <img class="img img-thumbnail mb-3" id="example-mart-logo" src="<?= $logo_file ?>" alt="<?= $business['business_local_names'][$lang] ?>" style="width:5em;" />
                                        <?php endif; ?>
                                        <h3 id="example-mart-primary"><?= lang('Business.marketplace') ?>: <?= $business['business_local_names'][$lang] ?></h3>
                                        <p id="example-mart-text"><?= lang('Business.marketplace-example-text') ?></p>
                                    </div>
                                </div>
                            </div>
                            <!-- CLEAR CACHE -->
                            <h3 class="mt-5 pt-5" id="clear-cache-header"><?= lang('Business.btn-clear-cache') ?></h3>
                            <p class="mt-3"><?= lang('Business.clear-cache') ?></p>
                            <div class="text-end">
                                <button id="btn-clear-cache" type="button" class="btn btn-primary"><?= lang('Business.btn-clear-cache') ?></button>
                            </div>
                            <div id="clear-cache-status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="script_action" id="script_action" value="" />
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // SLUG
            $('#btn-update-slug').click(function () {
                $(this).addClass('d-none');
                $('#btn-update-slug-confirm').removeClass('d-none');
            });
            $('#btn-update-slug-confirm').click(function () {
                let business_name = $('#business_name').val();
                $('#btn-update-slug-confirm').prop('disabled', true);
                $.post(
                    "<?= base_url('helper/generate-slug') ?>",
                    {name: business_name},
                    function (response) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            $('#business_slug').val(response.slug);
                        } else {
                            toastr.error(response.message);
                        }
                        $('#btn-update-slug').removeClass('d-none');
                        $('#btn-update-slug-confirm').prop('disabled', false).addClass('d-none');
                    },
                    "json"
                ).fail(function (response) {
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            $('#business_slug').change(function () {
                let slug = $(this).val();
                slug = slug.toLowerCase();
                slug = slug.replace(/[^a-z-]/g, '');
                $(this).val(slug);
            });
            $('#contact_phone_number').on('change', function () {
                let phone_number = $(this).val(),
                    country_code = '<?= $business['country_code'] ?>';
                $.post(
                    "<?= base_url('helper/format-phone-number') ?>",
                    {phone_number: phone_number, country_code: country_code},
                    function (response, status) {
                        if (response.status === "OK") {
                            $('#contact_phone_number').val(response.e164);
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
            // SAVE
            $('#btn-save-1, #btn-save-2, #btn-save-3, #btn-save-4, #btn-save-5, #btn-save-6').on('click', function (e) {
                e.preventDefault();
                <?php
                $all_fields = ['business_type_id', 'business_name', 'business_slug', 'allow_advance_booking', 'tax_percentage', 'tax_inclusive', 'live_status', 'mart_primary_color', 'mart_text_color', 'mart_background_color', 'currency_code', 'shipping_options', 'shipping_fee_taxable'];
                gen_js_fields_checker($all_fields);
                $all_fields[] = 'contact_email_address';
                $all_fields[] = 'contact_phone_number';
                $all_fields[] = 'contact_website';
                foreach ($all_languages as $lang_code => $language_name) {
                    $all_fields[] = 'business_local_names_' . $lang_code;
                    $all_fields[] = 'mart_meta_description_' . $lang_code;
                    $all_fields[] = 'mart_meta_keywords_' . $lang_code;
                    $all_fields[] = 'mart_store_intro_paragraph_' . $lang_code;
                }
                foreach ($social_medias as $code => $social_name) {
                    $all_fields[] = 'social_media_' . $code;
                }
                $all_fields[] = 'script_action';
                ?>
                $('#btn-save').prop('disabled', true);
                $('#script_action').val('save_business');
                $.post(
                    "<?= base_url('/admin/business') ?>",
                    <?php gen_json_fields_to_fields($all_fields) ?>,
                    function (response, status) {
                        $('#btn-save').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
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
            // MART COLORS
            let setColor = function () {
                let primary = $('#mart_primary_color').val(),
                    text = $('#mart_text_color').val(),
                    background = $('#mart_background_color').val();
                $('#example-mart-primary').css('color', primary);
                $('#example-mart-text').css('color', text);
                $('#example-mart-background').css('background-color', background);
            }
            setColor();
            $('.mart-reset-color').change(function () {
                setColor();
            });
            // LOGO
            $('#btn-upload-logo').on('click', function (e) {
                e.preventDefault();
                // check if the file is selected
                if ($('#logo').val() === '') {
                    toastr.warning('<?= lang('System.response-msg.error.please-check-empty-field') ?>');
                    $('#logo').focus();
                    return;
                }
                $('#btn-upload-logo').prop('disabled', true);
                // submit #form-upload-avatar form in AJAX
                $.ajax({
                    url: '<?= base_url('/admin/business') ?>',
                    type: 'POST',
                    data: new FormData($('#form-upload-logo')[0]),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        $('#btn-upload-logo').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#btn-upload-logo').prop('disabled', false);
                        let response = JSON.parse(xhr.responseText);
                        let message = response.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                        toastr.error(message);
                    }
                });
            });
            $('#btn-remove-logo').on('click', function (e) {
                e.preventDefault();
                $('#btn-remove-logo').hide();
                $('#btn-remove-logo-confirm').show();
            });
            $('#btn-remove-logo-confirm').on('click', function (e) {
                e.preventDefault();
                $('#btn-remove-logo-confirm').prop('disabled', true);
                $.ajax({
                    url: '<?= base_url('/admin/business') ?>',
                    type: 'POST',
                    data: {
                        script_action: 'remove_logo'
                    },
                    success: function (response) {
                        $('#btn-remove-logo-confirm').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#btn-remove-logo-confirm').prop('disabled', false);
                        let response = JSON.parse(xhr.responseText);
                        let message = response.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                        toastr.error(message);
                    }
                });
            });
            // HEADER IMG
            $('#btn-upload-header-img').on('click', function (e) {
                e.preventDefault();
                // check if the file is selected
                if ($('#header-img').val() === '') {
                    toastr.warning('<?= lang('System.response-msg.error.please-check-empty-field') ?>');
                    $('#header-img').focus();
                    return;
                }
                $('#btn-upload-header-img').prop('disabled', true);
                // submit #form-upload-avatar form in AJAX
                $.ajax({
                    url: '<?= base_url('/admin/business') ?>',
                    type: 'POST',
                    data: new FormData($('#form-upload-header-img')[0]),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        $('#btn-upload-header-img').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#btn-upload-header-img').prop('disabled', false);
                        let response = JSON.parse(xhr.responseText);
                        let message = response.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                        toastr.error(message);
                    }
                });
            });
            $('#btn-remove-header-img').on('click', function (e) {
                e.preventDefault();
                $('#btn-remove-header-img').hide();
                $('#btn-remove-header-img-confirm').show();
            });
            $('#btn-remove-header-img-confirm').on('click', function (e) {
                e.preventDefault();
                $('#btn-remove-header-img-confirm').prop('disabled', true);
                $.ajax({
                    url: '<?= base_url('/admin/business') ?>',
                    type: 'POST',
                    data: {
                        script_action: 'remove_header_img'
                    },
                    success: function (response) {
                        $('#btn-remove-header-img-confirm').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#btn-remove-header-img-confirm').prop('disabled', false);
                        let response = JSON.parse(xhr.responseText);
                        let message = response.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                        toastr.error(message);
                    }
                });
            });
            // CACHE
            $('#btn-clear-cache').click(function (e) {
                e.preventDefault();
                $('#clear-cache-status').html('');
                $(this).prop('disabled', true);
                $.ajax({
                    url: '<?= getenv('marketplace_site') ?>@<?= $business['business_slug'] ?>/clear-cache',
                    type: 'GET',
                    success: function (response) {
                        $('#btn-clear-cache').prop('disabled', false);
                        if (response.statuses) {
                            let msg = '<code>processing</code><br>';
                            $.each(response.statuses, function(i, data) {
                                msg += '<code>' + data + '</code><br>';
                            });
                            msg += '<code>done</code><br>';
                            $('#clear-cache-status').html(msg);
                        } else {
                            toastr.error('<?= lang('System.response-msg.error.generic') ?>');
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#btn-clear-cache').prop('disabled', false);
                        let response = JSON.parse(xhr.responseText);
                        let message = response.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                        toastr.error(message);
                    }
                });
            });
        });
    </script>
<?php $this->endSection() ?>