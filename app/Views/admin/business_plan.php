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
                    <?php if ('free' == $business['contract_plan']) : ?>
                        <p><?= lang('BusinessPlan.you-are-on-free-plan') ?></p>
                        <h4><?= lang('BusinessPlan.upgrade-options') ?></h4>
                        <?php $paid_plans = ['basic', 'standard', 'premium']; ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th class="text-center"><?= lang('BusinessPlan.table.features') ?></th>
                                    <th class="text-center"><?= lang('BusinessMaster.enum.contract_plan.free') ?></th>
                                    <?php foreach ($paid_plans as $pn) : ?>
                                        <th class="text-center"><?= lang('BusinessMaster.enum.contract_plan.' . $pn) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= lang('BusinessPlan.table.monthly-plan') ?></td>
                                    <td class="text-center"><?= lang('BusinessPlan.table.you-are-here') ?></td>
                                    <?php foreach ($paid_plans as $pn) : ?>
                                        <td class="text-center">
                                            <b><?= format_price($plans[$pn]['monthly'][0], $business['currency_code']) ?></b><br/>
                                            <small><s><?= format_price($plans[$pn]['monthly'][1], $business['currency_code']) ?></s></small><br/>
                                            <a href="#" class="btn btn-outline-primary"><?= lang('BusinessPlan.table.upgrade-to-' . $pn) ?></a>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <td><?= lang('BusinessPlan.table.annual-plan') ?></td>
                                    <td class="text-center"><?= lang('BusinessPlan.table.you-are-here') ?></td>
                                    <?php foreach ($paid_plans as $pn) : ?>
                                        <td class="text-center">
                                            <b><?= format_price($plans[$pn]['annual'][0], $business['currency_code']) ?></b><br/>
                                            <small><s><?= format_price($plans[$pn]['annual'][1], $business['currency_code']) ?></s></small><br/>
                                            <a href="#" class="btn btn-outline-primary"><?= lang('BusinessPlan.table.upgrade-to-' . $pn) ?></a><br/>
                                            <?php
                                            $total_monthly = $plans[$pn]['monthly'][0] * 12; $savings_percentage = number_format((($total_monthly - $plans[$pn]['annual'][0]) / $total_monthly) * 100, 2);
                                            echo lang('BusinessPlan.table.you-save', [$savings_percentage]);
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php print_options($options); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <?php if ('basic' == $business['contract_plan']) : ?>
                            // upgrade option to standard or premium
                        <?php elseif ('standard' == $business['contract_plan']) : ?>
                            // upgrade option to premium
                        <?php endif; ?>
                        // renewal options - 14/30 days before expiry
                    <?php endif; ?>
                    <h4> contract history </h4>
                    <div class="table-responsive">
                        // contracts...
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>