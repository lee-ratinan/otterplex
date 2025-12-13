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
                            <?php
                            echo '<h3>' . lang('Business.subtitle.generic-information') . '</h3>';
                            echo build_form_input('business_type_id', lang('BusinessMaster.field.business_type_id'), [
                                'type' => 'select',
                            ], $business['business_type_id'], '', $business_types);
                            echo build_form_input('business_name', lang('BusinessMaster.field.business_name'), [
                                'type' => 'text',
                            ], $business['business_name']);
                            echo build_form_input('business_slug', lang('BusinessMaster.field.business_slug'), [
                                'type'             => 'text',
                                'data-explanation' => lang('BusinessMaster.explanation.business_slug')
                            ], $business['business_slug']);
                            foreach ($all_languages as $lang_code => $language_name) {
                                echo build_form_input('business_local_names_' . $lang_code, lang('BusinessMaster.field.business_local_names') . ' (' . $language_name . ')', [
                                    'type' => 'text',
                                ], $business['business_local_names'][$lang_code]);
                            }
                            // country code is not updatable
                            echo '<h3>' . lang('Business.subtitle.tax-information') . '</h3>';
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
                            echo '<h3>' . lang('Business.subtitle.mart-decoration') . '</h3>';
                            echo build_form_input('mart_primary_color', lang('BusinessMaster.field.mart_primary_color'), [
                                'type' => 'color',
                            ], '#' . $business['mart_primary_color'], 'mart-reset-color');
                            echo build_form_input('mart_text_color', lang('BusinessMaster.field.mart_text_color'), [
                                'type' => 'color',
                            ], '#' . $business['mart_text_color'], 'mart-reset-color');
                            echo build_form_input('mart_background_color', lang('BusinessMaster.field.mart_background_color'), [
                                'type' => 'color',
                            ], '#' . $business['mart_background_color'], 'mart-reset-color');
                            ?>
                            <div class="row">
                                <div class="col p-5 m-3" id="example-mart-background">
                                    <img id="example-mart-logo" src="<?= $logo_file ?>" alt="OtterNova" style="width:5em;" />
                                    <h3 id="example-mart-primary"><?= lang('Business.marketplace') ?>: <?= $business['business_local_names'][$lang] ?></h3>
                                    <p id="example-mart-text"><?= lang('Business.marketplace-example-text') ?></p>
                                </div>
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save"><?= lang('System.buttons.save') ?></button>
                            </div>
                            <!-- UPLOAD AVATAR -->
                            <h3><?= lang('Business.upload-logo') ?></h3>
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
                        </div>
                    </div>
                    <hr class="my-3" />
                    <h2><?= lang('Business.contracts') ?></h2>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="min-width:100px"><?= lang('BusinessContract.field.package_id') ?></th>
                                <th style="min-width:150px"><?= lang('BusinessContract.field.invoice_number') ?></th>
                                <th style="min-width:150px"><?= lang('BusinessContract.field.contract_start') ?></th>
                                <th style="min-width:150px"><?= lang('BusinessContract.field.contract_expiry') ?></th>
                                <th style="min-width:120px"><?= lang('BusinessContract.field.total_amount') ?></th>
                                <th style="min-width:120px"><?= lang('BusinessContract.field.paid_amount') ?></th>
                                <th style="min-width:150px"><?= lang('BusinessContract.field.financial_status') ?></th>
                                <th style="min-width:100px"><?= lang('System.buttons.view-more') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($contracts as $contract) : ?>
                                <tr>
                                    <td><?= $contract['package_name'] ?></td>
                                    <td><?= $contract['invoice_number'] ?></td>
                                    <td><?= format_date($contract['contract_start']) ?></td>
                                    <td><?= format_date($contract['contract_expiry']) ?></td>
                                    <td class="text-end"><?= format_price($contract['total_amount'], $contract['country_code']) ?></td>
                                    <td class="text-end"><?= format_price($contract['paid_amount'], $contract['country_code']) ?></td>
                                    <td><?= lang('BusinessContract.enum.financial_status.' . $contract['financial_status']) ?></td>
                                    <?php
                                    $payments = [];
                                    if (isset($contract['payments'])) {
                                        foreach ($contract['payments'] as $payment) {
                                            $payments[] = [
                                                'payment_method' => lang('BusinessContractPayment.enum.payment_method.' . $payment['payment_method']),
                                                'payment_notes'  => $payment['payment_notes'],
                                                'staff_comment'  => $payment['staff_comment'],
                                                'amount_paid'    => format_price($payment['amount_paid'], $contract['country_code']),
                                                'payment_status' => lang('BusinessContractPayment.enum.payment_status.' . $payment['payment_status'])
                                            ];
                                        }
                                    }
                                    ?>
                                    <td><a class="btn-modal" href="#" data-bs-toggle="modal" data-bs-target="#contract-modal"
                                           data-package="<?= $contract['package_name'] ?>"
                                           data-invoice-number="<?= $contract['invoice_number'] ?>"
                                           data-start="<?= format_date($contract['contract_start']) ?>"
                                           data-expiry="<?= format_date($contract['contract_expiry']) ?>"
                                           data-invoiced-amount="<?= $contract['invoiced_amount'] != $contract['total_amount'] ? format_price($contract['invoiced_amount'], $contract['country_code']) : '' ?>"
                                           data-discount-amount="<?= $contract['discount_amount'] != 0 ? format_price($contract['discount_amount'], $contract['country_code']) : '' ?>"
                                           data-total-amount="<?= format_price($contract['total_amount'], $contract['country_code']) ?>"
                                           data-paid-amount="<?= format_price($contract['paid_amount'], $contract['country_code']) ?>"
                                           data-status="<?= lang('BusinessContract.enum.financial_status.' . $contract['financial_status']) ?>"
                                           data-payments="<?= htmlspecialchars(json_encode($payments), ENT_QUOTES, 'UTF-8') ?>"
                                        ><?= lang('System.buttons.view-more') ?></a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end"><a class="btn btn-primary" href="<?= base_url('admin/business/contract-renewal') ?>"><?= lang('Business.contract-renew') ?></a></div>
                    <div class="modal fade" id="contract-modal" tabindex="-1" aria-labelledby="contract-modal-label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="contract-modal-label">[INVOICE-NUMBER]</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-sm table-borderless">
                                        <tr><td style="width:50%;"><?= lang('BusinessContract.field.package_id') ?></td><td id="modal-package"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.invoice_number') ?></td><td id="modal-invoice-number"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.contract_start') ?></td><td id="modal-start"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.contract_expiry') ?></td><td id="modal-expiry"></td></tr>
                                        <tr id="modal-invoice-row"><td><?= lang('BusinessContract.field.invoiced_amount') ?></td><td id="modal-invoiced-amount"></td></tr>
                                        <tr id="modal-discount-row"><td><?= lang('BusinessContract.field.discount_amount') ?></td><td id="modal-discount-amount"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.total_amount') ?></td><td id="modal-total-amount"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.paid_amount') ?></td><td id="modal-paid-amount"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.financial_status') ?></td><td id="modal-status"></td></tr>
                                    </table>
                                    <h4><?= lang('Business.payment-records') ?></h4>
                                    <div id="modal-payment-records"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <label for="script_action"><input type="hidden" name="script_action" id="script_action" value="" /></label>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // MODAL
            $('.btn-modal').click(function (e) {
                e.preventDefault();
                $('#contract-modal-label').html($(this).data('invoice-number'));
                $('#modal-package').html($(this).data('package'));
                $('#modal-invoice-number').html($(this).data('invoice-number'));
                $('#modal-start').html($(this).data('start'));
                $('#modal-expiry').html($(this).data('expiry'));
                $('#modal-invoiced-amount').html($(this).data('invoiced-amount'));
                $('#modal-discount-amount').html($(this).data('discount-amount'));
                $('#modal-total-amount').html($(this).data('total-amount'));
                $('#modal-paid-amount').html($(this).data('paid-amount'));
                $('#modal-status').html($(this).data('status'));
                $('#modal-invoice-row, #modal-discount-row').removeClass('d-none');
                if ('' === $(this).data('invoiced-amount')) {
                    $('#modal-invoice-row').addClass('d-none');
                }
                if ('' === $(this).data('discount-amount')) {
                    $('#modal-discount-row').addClass('d-none');
                }
                let payments = $(this).data('payments');
                $('#modal-payment-records').html('<?= lang('System.generic-term.no-data') ?>');
                if (0 < payments.length) {
                    let payment_lines = '<table class="table table-sm table-borderless">';
                    $.each(payments, function (i, data) {
                        payment_lines += '<tr><td style="width:50%"><?= lang('BusinessContractPayment.field.payment_method') ?></td><td>' + data.payment_method + '</td></tr>';
                        payment_lines += '<tr><td><?= lang('BusinessContractPayment.field.payment_notes') ?></td><td>' + data.payment_notes + '</td></tr>';
                        payment_lines += '<tr><td><?= lang('BusinessContractPayment.field.amount_paid') ?></td><td>' + data.amount_paid + '</td></tr>';
                        payment_lines += '<tr><td><?= lang('BusinessContractPayment.field.payment_status') ?></td><td>' + data.payment_status + '</td></tr>';
                        payment_lines += '<tr><td><?= lang('BusinessContractPayment.field.staff_comment') ?></td><td>' + data.staff_comment + '</td></tr>';
                    });
                    payment_lines += '</table>';
                    $('#modal-payment-records').html(payment_lines);
                }
            });
            // ON KEYUP
            $('#business_name').keyup(function () {
                let business_name = $(this).val();
                $.post(
                    "<?= base_url('helper/generate-slug') ?>",
                    {name: business_name},
                    function (response) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            $('#business_slug').val(response.slug);
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
            $('#business_slug').change(function () {
                let slug = $(this).val();
                slug = slug.toLowerCase();
                slug = slug.replace(/[^a-z-]/g, '');
                $(this).val(slug);
            });
            // SAVE
            $('#btn-save').on('click', function (e) {
                e.preventDefault();
                // business_local_names_en
                <?php
                $all_fields = ['business_type_id', 'business_name', 'business_slug', 'tax_percentage', 'tax_inclusive', 'mart_primary_color', 'mart_text_color', 'mart_background_color'];
                foreach ($all_languages as $lang_code => $language_name) {
                    $all_fields[] = 'business_local_names_' . $lang_code;
                }
                gen_js_fields_checker($all_fields);
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
        });
    </script>
<?php $this->endSection() ?>