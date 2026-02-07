<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table id="fee-table" class="table table-sm table-hover table-striped">
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
                            <?php $max_range = 0.00; $count = count($rates); ?>
                            <?php foreach ($rates as $i => $rate): ?>
                            <?php if (0 < $rate['price_range_to']) { $max_range = $rate['price_range_to'] + 0.01; } else { $max_range = -1; } ?>
                            <?php $i+=1; ?>
                            <tr>
                                <td><?= format_price($rate['price_range_from'], $business['currency_code']) ?></td>
                                <td><?= ((0 > $rate['price_range_to']) ? '∞' : format_price($rate['price_range_to'], $business['currency_code'])) ?></td>
                                <td><?= format_price($rate['shipping_rate'], $business['currency_code']) ?></td>
                                <td><?= $rate['rate_comment'] ?></td>
                                <td class="text-end">
                                    <?php if ($i == $count) : ?>
                                    <button class="btn btn-outline-danger btn-sm btn-remove" data-id="<?= $rate['id'] ?>">
                                        <?= lang('System.buttons.remove') ?>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm btn-remove-confirm d-none" id="btn-remove-confirm-<?= $rate['id'] ?>" data-id="<?= $rate['id'] ?>">
                                        <?= lang('System.buttons.remove-confirm') ?>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row <?= (-1 == $max_range) ? 'd-none' : '' ?>">
                        <div class="col-12 col-md-6">
                            <h3 class="mt-5 pt-5"><?= lang('Business.shipping-rate.add-new') ?></h3>
                            <?php
                            echo build_form_input('price_range_from', lang('BusinessShippingFee.field.price_range_from'), [
                                'type'             => 'text',
                                'data-explanation' => lang('BusinessShippingFee.explanation.price_range_from'),
                                'readonly'         => 'readonly',
                            ], $max_range);
                            echo build_form_input('price_range_to', lang('BusinessShippingFee.field.price_range_to'), [
                                'type'             => 'text',
                                'data-explanation' => lang('BusinessShippingFee.explanation.price_range_to'),
                            ], '');
                            echo build_form_input('shipping_rate', lang('BusinessShippingFee.field.shipping_rate'), [
                                'type' => 'number',
                            ], 0);
                            echo build_form_input('rate_comment', lang('BusinessShippingFee.field.rate_comment'), [
                                'type' => 'text',
                            ], '');
                            ?>
                            <input type="hidden" name="business_id" id="business_id" value="<?= $business['business_id'] ?>" />
                            <input type="hidden" name="action" id="action" value="insert-shipping-rate" />
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save"><?= lang('System.buttons.save') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('#fee-table').DataTable();
            $('#price_range_to').change(function () {
                let min_val = <?= $max_range ?>,
                    current_val = $(this).val();
                if (current_val <= min_val) {
                    $(this).val('').focus();
                    toastr.warning('<?= lang('Business.shipping-rate.max-price-error') ?>');
                }
            });
            $('#shipping_rate').change(function () {
                let current_val = $(this).val();
                if (isNaN(current_val) || 0 > current_val) {
                    $(this).val('').focus();
                    toastr.warning('<?= lang('Business.shipping-rate.shipping-rate-error') ?>');
                }
            });
            $('#btn-save').click(function (e) {
                e.preventDefault();
                <?php
                $fields = ['price_range_from', 'shipping_rate'];
                gen_js_fields_checker($fields);
                ?>
                $('#btn-save').prop('disabled', true);
                $.post(
                    "<?= base_url('admin/shipping-fee') ?>",
                    <?php $fields[] = 'price_range_to'; $fields[] = 'business_id'; $fields[] = 'rate_comment'; $fields[] = 'action'; gen_json_fields_to_fields($fields) ?>,
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
            }); // submit
            $('.btn-remove').click(function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                $('#btn-remove-confirm-' + id).removeClass('d-none');
                $(this).addClass('d-none');
            });
            $('.btn-remove-confirm').click(function (e) {
                e.preventDefault();
                let row_id = $(this).data('id');
                $(this).prop('disabled', true);
                $.post(
                    "<?= base_url('admin/shipping-fee') ?>",
                    {
                        action: 'delete-rate',
                        id: row_id
                    },
                    function (response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            })
        });
    </script>
<?php $this->endSection() ?>