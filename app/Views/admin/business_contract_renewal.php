<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
<?php if (isset($unpaid_pending)) : ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h1><?= $unpaid_pending ?></h1>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><?= lang('BusinessContract.field.package_id') ?></td>
                                    <td><?= $record['package_name'] ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang('BusinessContract.field.invoice_number') ?></td>
                                    <td><?= $record['invoice_number'] ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang('BusinessContract.field.contract_start') ?></td>
                                    <td><?= format_date($record['contract_start']) ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang('BusinessContract.field.contract_expiry') ?></td>
                                    <td><?= format_date($record['contract_expiry']) ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang('BusinessContract.field.total_amount') ?></td>
                                    <td><?= format_price($record['total_amount'], $session->business['country_code']) ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang('BusinessContract.field.paid_amount') ?></td>
                                    <td><?= format_price($record['paid_amount'], $session->business['country_code']) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end" style="font-size:2em">
                                        <small><?= lang('Business.renewal.amount-due') ?></small>
                                        <?= format_price($record['total_amount']-$record['paid_amount'], $session->business['country_code']) ?>
                                    </td>
                                </tr>
                            </table>
                            <?php if (!empty($payments)) : ?>
                                <h2><?= lang('Business.renewal.payments') ?></h2>
                                <table class="table table-sm table-borderless">
                                    <?php foreach ($payments as $payment) : ?>
                                        <tr>
                                            <td><?= lang('BusinessContractPayment.field.amount_paid') ?></td>
                                            <td><?= format_price($payment['amount_paid'], $session->business['country_code']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('BusinessContractPayment.field.payment_method') ?></td>
                                            <td><?= $payment['payment_method'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('BusinessContractPayment.field.payment_notes') ?></td>
                                            <td><?= $payment['payment_notes'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('BusinessContractPayment.field.staff_comment') ?></td>
                                            <td><?= $payment['staff_comment'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('BusinessContractPayment.field.payment_status') ?></td>
                                            <td><?= $payment['payment_status'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('Business.renewal.payment-at') ?></td>
                                            <td class="utc-to-local"><?= $payment['created_at'] ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr /></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php endif; ?>
                            <h2><?= lang('Business.renewal.how-to-pay') ?></h2>
                            <a href="#" class="btn btn-outline-primary w-100 py-3 mb-3">
                                <img class="img float-end" src="<?= base_url('assets/img/visa-master.png') ?>" alt="Credit Card: VISA/MasterCard" style="max-height:2em" />
                                <b class="float-start text-start"><i class="fa-solid fa-credit-card"></i><br><?= lang('Business.renewal.pay-by-credit-card') ?></b>
                            </a>
                            <a href="#" class="btn btn-outline-primary w-100 py-3 mb-3">
                                <img class="img float-end" src="<?= base_url('assets/img/thai-qr.png') ?>" alt="Thai PromptPay" style="max-height:2em" />
                                <b class="float-start text-start"><i class="fa-solid fa-qrcode"></i><br><?= lang('Business.renewal.pay-by-qr-thailand') ?></b>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h2><?= lang('Business.packages.pick-one') ?></h2>
                            <div class="row">
                                <div class="col-6">
                                    <h6><?= lang('Business.packages.validity.month') ?></h6>
                                    <?php foreach ($packages['month'] as $package) : ?>
                                        <a class="btn btn-outline-primary w-100 mb-3 btn-package"
                                           data-package-id="<?= $package['id'] ?>"
                                           data-start-date="<?= $package['package_start_date'] ?>"
                                           data-expiry-date="<?= $package['package_expiry_date'] ?>"
                                           data-price="<?= $package['package_price'] ?>"
                                        ><?= $package['package_name'] ?> : <?= format_price($package['package_price'], 'TH') ?></a>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-6">
                                    <h6><?= lang('Business.packages.validity.year') ?></h6>
                                    <?php foreach ($packages['year'] as $package) : ?>
                                        <a class="btn btn-outline-primary w-100 mb-3 btn-package"
                                           data-package-id="<?= $package['id'] ?>"
                                           data-start-date="<?= $package['package_start_date'] ?>"
                                           data-expiry-date="<?= $package['package_expiry_date'] ?>"
                                           data-price="<?= $package['package_price'] ?>"
                                        ><?= $package['package_name'] ?> : <?= format_price($package['package_price'], 'TH') ?></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <label class="d-none" for="package_id"><input type="text" id="package_id" name="package_id" value="" /></label>
                            <?php
                            echo build_form_input('contract_start', lang('BusinessContract.field.contract_start'), [
                                'type'     => 'date',
                                'readonly' => 'true',
                            ], '', 'border-0');
                            echo build_form_input('contract_expiry', lang('BusinessContract.field.contract_expiry'), [
                                'type'     => 'date',
                                'readonly' => 'true',
                            ], '', 'border-0');
                            echo build_form_input('total_amount', lang('BusinessContract.field.total_amount'), [
                                'type'     => 'text',
                                'readonly' => 'true',
                            ], '', 'border-0');
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-renew"><?= lang('Business.contract-renew') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('.btn-package').click(function (e) {
                e.preventDefault();
                $('#package_id').val($(this).data('package-id'));
                $('#contract_start').val($(this).data('start-date'));
                $('#contract_expiry').val($(this).data('expiry-date'));
                $('#total_amount').val($(this).data('price'));
            });
            $('#btn-renew').click(function (e) {
                e.preventDefault();
                <?php
                gen_js_fields_checker(['contract_start', 'contract_expiry', 'total_amount', 'package_id']);
                ?>
                $('#btn-renew').prop('disabled', true);
                $.post(
                    "<?= base_url('/admin/business/contract-renewal') ?>",
                    <?php gen_json_fields_to_fields(['contract_start', 'contract_expiry', 'total_amount', 'package_id']) ?>,
                    function (response, status) {
                        $('#btn-renew').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-renew').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
        });
    </script>
<?php endif; ?>
<?php $this->endSection() ?>