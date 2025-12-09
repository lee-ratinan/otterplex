<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= lang('Business.title', [$business['business_local_names'][$lang] ?? $business['business_name']]) ?></h2>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <?php
                            echo '<h3>' . lang('Business.subtitle.generic-information') . '</h3>';
                            echo build_form_input('business_type_id', lang('BusinessMaster.field.business_type_id'), [
                                'type' => 'select',
                            ], $business['business_type_id'], '', $business_types);
                            echo build_form_input('business_name', lang('BusinessMaster.field.business_name'), [
                                'type' => 'text',
                            ], $business['business_name']);
                            echo build_form_input('business_slug', lang('BusinessMaster.field.business_slug'), [
                                'type' => 'text',
                            ], $business['business_slug']);
                            // business_local_names x all_languages
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
                                'type' => 'select'
                            ], $business['tax_inclusive'], '', ['I' => lang('BusinessMaster.enum.tax_inclusive.I'), 'E' => lang('BusinessMaster.enum.tax_inclusive.E')]);
                            echo '<h3>' . lang('Business.subtitle.mart-decoration') . '</h3>';
                            echo build_form_input('mart_primary_color', lang('BusinessMaster.field.mart_primary_color'), [
                                'type' => 'color',
                            ], $business['mart_primary_color']);
                            echo build_form_input('mart_text_color', lang('BusinessMaster.field.mart_text_color'), [
                                'type' => 'color',
                            ], $business['mart_text_color']);
                            echo build_form_input('mart_background_color', lang('BusinessMaster.field.mart_background_color'), [
                                'type' => 'color',
                            ], $business['mart_background_color']);
                            echo 'UPLOAD LOGO';
                            ?>
                        </div>
                    </div>
                    <hr />
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
                                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#contract-modal"
                                           data-package="<?= $contract['package_name'] ?>"
                                           data-invoice-number="<?= $contract['invoice_number'] ?>"
                                           data-start="<?= format_date($contract['contract_start']) ?>"
                                           data-expiry="<?= format_date($contract['contract_expiry']) ?>"
                                           data-invoiced-amount="<?= lang('BusinessContract.field.invoiced_amount') ?>"
                                           data-discount-amount="<?= lang('BusinessContract.field.discount_amount') ?>"
                                           data-total-amount="<?= format_price($contract['total_amount'], $contract['country_code']) ?>"
                                           data-paid-amount="<?= format_price($contract['paid_amount'], $contract['country_code']) ?>"
                                           data-status="<?= lang('BusinessContract.enum.financial_status.' . $contract['financial_status']) ?>"
                                           data-payment="<?= json_encode($payments) ?>"
                                        ><?= lang('System.buttons.view-more') ?></a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <p><a class="btn btn-outline-primary" href="#"><?= lang('Business.contract-renew') ?></a></p>
                    <div class="modal fade" id="contract-modal" tabindex="-1" aria-labelledby="contract-modal-label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="contract-modal-label">[INVOICE-NUMBER]</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-sm table-borderless">
                                        <tr><td><?= lang('BusinessContract.field.package_id') ?></td><td id="modal-package"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.invoice_number') ?></td><td id="modal-invoice-number"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.contract_start') ?></td><td id="modal-start"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.contract_expiry') ?></td><td id="modal-expiry"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.invoiced_amount') ?></td><td id="modal-invoiced-amount="></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.discount_amount') ?></td><td id="modal-discount-amount"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.total_amount') ?></td><td id="modal-total-amount"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.paid_amount') ?></td><td id="modal-paid-amount"></td></tr>
                                        <tr><td><?= lang('BusinessContract.field.financial_status') ?></td><td id="modal-status"></td></tr>
                                        <tr><td><?= lang('Business.payment-records') ?></td><td id="payments"></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>