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
                            <div id="payment-form-promptpay">
                                <hr/>
                                <h3><?= lang('Business.business-plan.promptpay-qr.title') ?></h3>
                                <p><?= lang('Business.business-plan.promptpay-qr.instruction-1') ?></p>
                                <p><?= lang('Business.business-plan.promptpay-qr.instruction-2') ?></p>
                                <div class="text-center my-3">
                                    <img id="promptpay-qr" src="#" alt="QR Code" style="width:200px;height:200px;"/>
                                </div>
                                <p><?= lang('Business.business-plan.payment.instruction-for-pending-payment-confirmation') ?></p>
                            </div>
                            <div id="payment-form-bank-transfer">
                                <hr/>
                                <h3><?= lang('Business.business-plan.bank-transfer-section.title') ?></h3>
                                <p><?= lang('Business.business-plan.bank-transfer-section.instruction-1') ?></p>
                                <p><?= lang('Business.business-plan.bank-transfer-section.instruction-2') ?></p>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.bank-name.title') ?></td>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.bank-name.value') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.swift-code.title') ?></td>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.swift-code.value') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.account-name.title') ?></td>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.account-name.value') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.account-number.title') ?></td>
                                        <td><?= lang('Business.business-plan.bank-transfer-section.account-number.value') ?></td>
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
<?php $this->endSection() ?>