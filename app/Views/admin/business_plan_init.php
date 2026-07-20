<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h2><?= lang('Admin.pages.business-plan-init') ?></h2>
                            <div class="row">
                                <div class="col-6">
                                    <p>
                                        <b><?= lang('BusinessMaster.field.contract_plan') ?></b><br/>
                                        <?= lang('BusinessMaster.enum.contract_plan.' . $plan_name) ?>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p>
                                        <b><?= lang('BusinessMaster.field.contract_duration') ?></b><br/>
                                        <?= lang('BusinessMaster.enum.contract_duration.' . $plan_duration) ?>
                                    </p>
                                </div>
                            </div>
                            <p>
                                <b><?= lang('Business.business-plan.estimated-expiry') ?></b><br/>
                                <?= format_date($expiry_date, $session->lang) ?><br/>
                                <small><em><?= lang('Business.business-plan.expiry-note') ?></em></small>
                            </p>
                            <p>
                                <b><?= lang('Business.business-plan.timezone') ?></b><br/>
                                <?= $tz_name ?>
                            </p>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <b><?= lang('Business.business-plan.amount-due') ?></b>
                                </div>
                                <div class="col-6 text-end">
                                    <h5><?= format_price($act_price, $session->business['currency_code']) ?></h5>
                                    <s><?= format_price($fr_price, $session->business['currency_code']) ?></s>
                                </div>
                            </div>
                            <p><label for="payment-method"><b><?= lang('Business.business-plan.payment-methods') ?></b></label></p>
                            <select class="form-select mb-3" id="payment-method">
                                <?php if ('TH' == $session->business['country_code']) : ?>
                                    <option value="promptpay"><?= lang('Business.business-plan.promptpay') ?></option>
                                    <option value="bank-transfer"><?= lang('Business.business-plan.bank-transfer') ?></option>
                                <?php endif; ?>
                            </select>
                            <div class="text-end mb-3">
                                <button class="btn btn-primary" id="btn-proceed"><?= lang('Business.business-plan.proceed') ?></button>
                            </div>
                            <div id="payment-form-promptpay" style="display:none;">
                                <hr/>
                                <div class="alert alert-success">
                                    <i class="fa-solid fa-check-circle"></i>
                                    <?= lang('Business.business-plan.instruction-received') ?>
                                </div>
                                <h3><?= lang('Business.business-plan.promptpay-qr.title') ?></h3>
                                <p><?= lang('Business.business-plan.promptpay-qr.instruction-1') ?></p>
                                <p><?= lang('Business.business-plan.promptpay-qr.instruction-2') ?></p>
                                <div class="text-center my-3">
                                    <img id="promptpay-qr" src="#" alt="QR Code" style="width:200px;height:200px;"/>
                                </div>
                                <p><?= lang('Business.business-plan.payment.instruction-for-pending-payment-confirmation') ?></p>
                            </div>
                            <div id="payment-form-bank-transfer" style="display:none;">
                                <hr/>
                                <div class="alert alert-success">
                                    <i class="fa-solid fa-check-circle"></i>
                                    <?= lang('Business.business-plan.instruction-received') ?>
                                </div>
                                <h3><?= lang('Business.business-plan.bank-transfer-section.title') ?></h3>
                                <p><?= lang('Business.business-plan.bank-transfer-section.instruction-1') ?></p>
                                <p><?= lang('Business.business-plan.bank-transfer-section.instruction-2') ?></p>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.bank-name') ?></td>
                                        <td id="table-bank-transfer-bank-name"></td>
                                    </tr>
                                    <tr>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.swift-code') ?></td>
                                        <td id="table-bank-transfer-swift-code"></td>
                                    </tr>
                                    <tr>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.account-name') ?></td>
                                        <td id="table-bank-transfer-account-name"></td>
                                    </tr>
                                    <tr>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.account-number') ?></td>
                                        <td id="table-bank-transfer-account-number"></td>
                                    </tr>
                                </table>
                                <p><?= lang('Business.business-plan.payment.instruction-for-pending-payment-confirmation') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#btn-proceed').click(function(e) {
                e.preventDefault();
                let paymentMethod = $('#payment-method').val();
                $('#btn-proceed').prop('disabled', true);
                $.post('<?= base_url('admin/business/plan/init') ?>',
                    {
                        payment_method: paymentMethod,
                        plan_name: '<?= $plan_name ?>',
                        plan_duration: '<?= $plan_duration ?>',
                        act_price: '<?= number_format($act_price, 2, '.', '') ?>'
                    },
                    function(response, status) {
                        $('#btn-proceed').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            if (response.payment_method === 'promptpay') {
                                $('#promptpay-qr').attr('src', response.data.promptpay_qr);
                                $('#payment-form-promptpay').slideDown();
                            } else {
                                // should be else-if
                                $('#table-bank-transfer-bank-name').html(response.data.bank_name);
                                $('#table-bank-transfer-swift-code').html(response.data.swift_code);
                                $('#table-bank-transfer-account-name').html(response.data.account_name);
                                $('#table-bank-transfer-account-number').html(response.data.account_number);
                                $('#payment-form-bank-transfer').slideDown();
                            }
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    'json'
                );
            });
        })
    </script>
<?php $this->endSection() ?>