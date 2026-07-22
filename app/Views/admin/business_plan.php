<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
<?php
function print_options($options) {
    $keys  = ['user_accounts', 'services', 'products', 'variants', 'images', 'branches', 'timeslots', 'sessions', 'about-us'];
    $plans = ['free', 'basic', 'standard', 'premium'];
    foreach ($keys as $key) {
        echo '<tr>';
        echo '<td>' . lang('BusinessPlan.table.' . $key) . '</td>';
        foreach ($plans as $plan) {
            if (in_array($key, ['timeslots', 'sessions', 'about-us'])) {
                echo '<td class="text-center">' . ($options[$plan][$key] ? '✅' : '❌') . '</td>';
            } else {
                echo '<td class="text-center">' . $options[$plan][$key] . '</td>';
            }
        }
        echo '</tr>';
    }
}
?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= lang('Admin.pages.business-plan') ?></h2>
                    <div class="row">
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
                    </div>
                    <?php if ($allowed) : ?>
                        <?php if ('free' == $business['contract_plan']) : ?>
                            <p><?= lang('BusinessPlan.you-are-on-free-plan') ?></p>
                        <?php endif; ?>
                        <h4><?= lang('BusinessPlan.upgrade-options') ?></h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th class="text-center"><?= lang('BusinessPlan.table.features') ?></th>
                                    <th class="text-center"><?= lang('BusinessMaster.enum.contract_plan.free') ?></th>
                                    <th class="text-center"><?= lang('BusinessMaster.enum.contract_plan.basic') ?></th>
                                    <th class="text-center"><?= lang('BusinessMaster.enum.contract_plan.standard') ?></th>
                                    <th class="text-center"><?= lang('BusinessMaster.enum.contract_plan.premium') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= lang('BusinessPlan.table.monthly-plan') ?></td>
                                    <td class="text-center">
                                        <!-- FREE -->
                                        <?php if ('free' == $business['contract_plan']) : ?>
                                            <i class="fa-solid fa-star text-warning"></i> <?= lang('BusinessPlan.table.you-are-here') ?>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- BASIC -->
                                        <?php if ('free' == $business['contract_plan']) : ?>
                                            <b><?= format_price($plans['basic']['monthly'][0], $business['currency_code']) ?></b><br/>
                                            <small><s><?= format_price($plans['basic']['monthly'][1], $business['currency_code']) ?></s></small><br/>
                                            <a href="<?= base_url('admin/business/plan/basic/monthly') ?>" class="btn btn-outline-primary"><?= lang('BusinessPlan.table.upgrade-to-basic') ?></a>
                                        <?php elseif ('basic' == $business['contract_plan'] && 'monthly' == $business['contract_duration']) : ?>
                                            <i class="fa-solid fa-star text-warning"></i> <?= lang('BusinessPlan.table.you-are-here') ?>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- STANDARD -->
                                        <?php if ('free' == $business['contract_plan'] ||
                                                 ('basic' == $business['contract_plan']) && 'monthly' == $business['contract_duration']) : ?>
                                            <b><?= format_price($plans['standard']['monthly'][0], $business['currency_code']) ?></b><br/>
                                            <small><s><?= format_price($plans['standard']['monthly'][1], $business['currency_code']) ?></s></small><br/>
                                            <a href="<?= base_url('admin/business/plan/standard/monthly') ?>" class="btn btn-outline-primary"><?= lang('BusinessPlan.table.upgrade-to-standard') ?></a>
                                        <?php elseif ('standard' == $business['contract_plan'] && 'monthly' == $business['contract_duration']) : ?>
                                            <i class="fa-solid fa-star text-warning"></i> <?= lang('BusinessPlan.table.you-are-here') ?>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- PREMIUM -->
                                        <?php if ('premium' == $business['contract_plan'] && 'monthly' == $business['contract_duration']) : ?>
                                            <i class="fa-solid fa-star text-warning"></i> <?= lang('BusinessPlan.table.you-are-here') ?>
                                        <?php elseif ('free' == $business['contract_plan'] || 'monthly' == $business['contract_duration']) : ?>
                                            <b><?= format_price($plans['premium']['monthly'][0], $business['currency_code']) ?></b><br/>
                                            <small><s><?= format_price($plans['premium']['monthly'][1], $business['currency_code']) ?></s></small><br/>
                                            <a href="<?= base_url('admin/business/plan/premium/monthly') ?>" class="btn btn-outline-primary"><?= lang('BusinessPlan.table.upgrade-to-premium') ?></a>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= lang('BusinessPlan.table.annual-plan') ?></td>
                                    <td class="text-center">
                                        <!-- FREE -->
                                        <?php if ('free' == $business['contract_plan']) : ?>
                                            <i class="fa-solid fa-star text-warning"></i> <?= lang('BusinessPlan.table.you-are-here') ?>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- BASIC -->
                                        <?php if ('free' == $business['contract_plan']) : ?>
                                            <b><?= format_price($plans['basic']['annually'][0], $business['currency_code']) ?></b><br/>
                                            <small><s><?= format_price($plans['basic']['annually'][1], $business['currency_code']) ?></s></small><br/>
                                            <a href="<?= base_url('admin/business/plan/basic/annually') ?>" class="btn btn-outline-primary"><?= lang('BusinessPlan.table.upgrade-to-basic') ?></a><br/>
                                            <?php
                                            $total_monthly = $plans['basic']['monthly'][0] * 12;
                                            $savings_percentage = number_format((($total_monthly - $plans['basic']['annually'][0]) / $total_monthly) * 100, 2);
                                            echo lang('BusinessPlan.table.you-save', [$savings_percentage]);
                                            ?>
                                        <?php elseif ('basic' == $business['contract_plan'] && 'annually' == $business['contract_duration']) : ?>
                                            <i class="fa-solid fa-star text-warning"></i> <?= lang('BusinessPlan.table.you-are-here') ?>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- STANDARD -->
                                        <?php if ('free' == $business['contract_plan'] ||
                                            ('basic' == $business['contract_plan']) && 'annually' == $business['contract_duration']) : ?>
                                            <b><?= format_price($plans['standard']['annually'][0], $business['currency_code']) ?></b><br/>
                                            <small><s><?= format_price($plans['standard']['annually'][1], $business['currency_code']) ?></s></small><br/>
                                            <a href="<?= base_url('admin/business/plan/standard/annually') ?>" class="btn btn-outline-primary"><?= lang('BusinessPlan.table.upgrade-to-standard') ?></a><br/>
                                            <?php
                                            $total_monthly = $plans['standard']['monthly'][0] * 12;
                                            $savings_percentage = number_format((($total_monthly - $plans['standard']['annually'][0]) / $total_monthly) * 100, 2);
                                            echo lang('BusinessPlan.table.you-save', [$savings_percentage]);
                                            ?>
                                        <?php elseif ('standard' == $business['contract_plan'] && 'annually' == $business['contract_duration']) : ?>
                                            <i class="fa-solid fa-star text-warning"></i> <?= lang('BusinessPlan.table.you-are-here') ?>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- PREMIUM -->
                                        <?php if ('premium' == $business['contract_plan'] && 'annually' == $business['contract_duration']) : ?>
                                            <i class="fa-solid fa-star text-warning"></i> <?= lang('BusinessPlan.table.you-are-here') ?>
                                        <?php elseif ('free' == $business['contract_plan'] || 'annually' == $business['contract_duration']) : ?>
                                            <b><?= format_price($plans['premium']['annually'][0], $business['currency_code']) ?></b><br/>
                                            <small><s><?= format_price($plans['premium']['annually'][1], $business['currency_code']) ?></s></small><br/>
                                            <a href="<?= base_url('admin/business/plan/premium/annually') ?>" class="btn btn-outline-primary"><?= lang('BusinessPlan.table.upgrade-to-premium') ?></a><br/>
                                            <?php
                                            $total_monthly = $plans['premium']['monthly'][0] * 12;
                                            $savings_percentage = number_format((($total_monthly - $plans['premium']['annually'][0]) / $total_monthly) * 100, 2);
                                            echo lang('BusinessPlan.table.you-save', [$savings_percentage]);
                                            ?>
                                        <?php else : ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php print_options($options); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning mb-3"><?= lang('Business.business-plan.pending-contract', [$invoice]) ?></div>
                    <?php endif; ?>
                    <h4 class="mt-5"><?= lang('Business.business-plan.contract-history') ?></h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('BusinessContract.field.plan_name') ?></th>
                                <th><?= lang('BusinessContract.field.contract_expiry') ?></th>
                                <th><?= lang('BusinessContract.field.invoice_number') ?></th>
                                <th><?= lang('BusinessContract.field.total_amount') ?></th>
                                <th><?= lang('BusinessContract.field.paid_amount') ?></th>
                                <th><?= lang('BusinessContract.field.financial_status') ?></th>
                                <th>
                                    <?= lang('Business.business-plan.invoice') ?> /
                                    <?= lang('Business.business-plan.receipt') ?>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($historical as $row) : ?>
                            <tr>
                                <td><?= lang('BusinessContract.enum.plan_name.' . $row['plan_name']) . ': ' . lang('BusinessContract.enum.plan_duration.' . $row['plan_duration']) ?></td>
                                <td><?= date(DATE_FORMAT_UI, strtotime($row['contract_expiry'])) ?></td>
                                <td><?= $row['invoice_number'] ?></td>
                                <td class="text-end"><?= format_price($row['total_amount'], $row['currency_code']) ?></td>
                                <td class="text-end"><?= format_price($row['paid_amount'], $row['currency_code']) ?></td>
                                <td class="text-center"><?= $row['financial_status'] ?></td>
                                <td></td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>