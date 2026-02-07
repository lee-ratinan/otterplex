<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('BusinessShippingFee.field.price_range_from') ?></th>
                                <th><?= lang('BusinessShippingFee.field.price_range_to') ?></th>
                                <th><?= lang('BusinessShippingFee.field.shipping_rate') ?></th>
                                <th><?= lang('BusinessShippingFee.field.rate_comment') ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rates as $rate): ?>
                            <tr>
                                <td><?= format_price($rate['price_range_from'], $business['currency_code']) ?></td>
                                <td><?= ((0 > $rate['price_range_to']) ? '^' : format_price($rate['price_range_to'], $business['currency_code'])) ?></td>
                                <td><?= format_price($rate['shipping_rate'], $business['currency_code']) ?></td>
                                <td><?= $rate['rate_comment'] ?></td>
                                <td>delete</td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h3 class="mt-5 pt-5"><?= lang('Business.shipping-rate.add-new') ?></h3>
                            <?php
                            echo build_form_input('price_range_from', lang('BusinessShippingFee.field.price_range_from'), [
                                'type' => 'number',
                                'min'  => 0,
                                'step' => 0.01
                            ], 0);
                            echo build_form_input('price_range_to', lang('BusinessShippingFee.field.price_range_to'), [
                                'type' => 'number',
                                'min'  => 1,
                                'step' => 0.01
                            ], 0);
                            echo build_form_input('shipping_rate', lang('BusinessShippingFee.field.shipping_rate'), [
                                'type' => 'number',
                                'min'  => 0,
                                'step' => 0.01
                            ], 0);
                            echo build_form_input('rate_comment', lang('BusinessShippingFee.field.rate_comment'), [
                                'type' => 'text',
                            ], '');
                            ?>
                            <input type="hidden" name="business_id" id="business_id" value="<?= $business['business_id'] ?>" />
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save-variant"><?= lang('System.buttons.save') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

        });
    </script>
<?php $this->endSection() ?>