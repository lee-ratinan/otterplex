<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12 col-lg-10 col-xxl-8">
            <div class="card">
                <div class="card-body p-3">
                    <h5><?= lang('Business.title') ?></h5>
                    <pre><?php print_r($business); ?></pre>
                    <hr />
                    <h5><?= lang('Business.contracts') ?></h5>
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                        <tr>
                            <th><?= lang('BusinessContract.field.package_id') ?></th>
                            <th><?= lang('BusinessContract.field.invoice_number') ?></th>
                            <th><?= lang('BusinessContract.field.contract_start') ?></th>
                            <th><?= lang('BusinessContract.field.contract_expiry') ?></th>
                            <th><?= lang('BusinessContract.field.invoiced_amount') ?></th>
                            <th><?= lang('BusinessContract.field.discount_amount') ?></th>
                            <th><?= lang('BusinessContract.field.total_amount') ?></th>
                            <th><?= lang('BusinessContract.field.paid_amount') ?></th>
                            <th><?= lang('BusinessContract.field.financial_status') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($contracts as $contract) : ?>
                            <tr>
                                <td><?= $contract['package_name'] ?></td>
                                <td><?= $contract['invoice_number'] ?></td>
                                <td><?= format_date($contract['contract_start']) ?></td>
                                <td><?= format_date($contract['contract_expiry']) ?></td>
                                <td class="text-end"><?= format_price($contract['invoiced_amount'], $contract['country_code']) ?></td>
                                <td class="text-end"><?= format_price($contract['discount_amount'], $contract['country_code']) ?></td>
                                <td class="text-end"><?= format_price($contract['total_amount'], $contract['country_code']) ?></td>
                                <td class="text-end"><?= format_price($contract['paid_amount'], $contract['country_code']) ?></td>
                                <td><?= lang('BusinessContract.enum.financial_status.' . $contract['financial_status']) ?></td>
                            </tr>
                            <?php if (isset($contract['payments'])) : ?>
                                <?php foreach ($contract['payments'] as $payment) : ?>
                                    <tr>
                                        <td>+</td>
                                        <td><?= lang('BusinessContractPayment.enum.payment_method.' . $payment['payment_method']) ?></td>
                                        <td colspan="2"><?= $payment['payment_notes'] ?></td>
                                        <td colspan="3"><?= $payment['staff_comment'] ?></td>
                                        <td class="text-end"><?= format_price($payment['amount_paid'], $contract['country_code']) ?></td>
                                        <td><?= lang('BusinessContractPayment.enum.payment_status.' . $payment['payment_status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <pre>
                        use cases:
                        - edit business itself ([business_master])
                        - show the contract and history of payments (business_contract, business_contract_payment)

                        <?php print_r($business_types); ?>
                        <?php print_r($all_languages); ?>
                    </pre>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>