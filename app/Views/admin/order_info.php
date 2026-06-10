<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col">
                            <h2><?= $order_detail['order_number'] ?></h2>
                            <hr />
                            <div class="row">
                                <div class="col-12 col-lg-8">
                                    <h3><?= lang('Order.line-items') ?></h3>
                                    <?php foreach($order_detail['line_items'] as $row) : ?>
                                        <div class="row">
                                            <div class="col-8 col-lg-9">
                                                <?php
                                                $product_local_names = json_decode($row['product_local_names'], true);
                                                $variant_local_names = json_decode($row['variant_local_names'], true);
                                                $product_name = $product_local_names[$lang] ?? $row['main_product_name'];
                                                $variant_name = $variant_local_names[$lang] ?? $row['main_variant_name'];
                                                ?>
                                                <img class="img img-thumbnail float-start me-2 mb-2" src="<?= base_url('file/product_image_' . $row['product_slug'] . '.jpg') ?>" alt="<?= $product_name ?>" style="max-width:150px" />
                                                <b><?= $product_name ?> - <?= $variant_name ?></b><br>
                                                <?= lang('OrderLineItem.field.unit_price') ?>: <?= format_price($row['unit_price'], $business['currency_code']) ?><br>
                                                SKU: <?= empty($row['variant_sku']) ? '-' : $row['variant_sku'] ?><br>
                                            </div>
                                            <div class="col-1"><?= number_format($row['line_quantity']) ?></div>
                                            <div class="col-3 col-lg-2 text-end"><?= format_price($row['line_subtotal'], $business['currency_code']) ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php foreach ($order_detail['booking_items'] as $row) : ?>
                                        <div class="row">
                                            <div class="col-8 col-lg-9">
                                                <?php
                                                $service_local_names = json_decode($row['service_local_names'], true);
                                                $variant_local_names = json_decode($row['variant_local_names'], true);
                                                $service_name = $service_local_names[$lang] ?? $row['main_service_name'];
                                                $variant_name = $variant_local_names[$lang] ?? $row['main_variant_name'];
                                                ?>
                                                <img class="img img-thumbnail float-start me-2 mb-2" src="<?= base_url('file/service_image_' . $row['service_slug'] . '.jpg') ?>" alt="<?= $service_name ?>" style="max-width:150px" />
                                                <b><?= $service_name ?> - <?= $variant_name ?></b><br>
                                                <?= $row['session_description'] ?><br>
                                                <?= lang('OrderLineItem.field.unit_price') ?>: <?= format_price($row['unit_price'], $business['currency_code']) ?><br>
                                            </div>
                                            <div class="col-1"><?= number_format($row['booking_quantity']) ?></div>
                                            <div class="col-3 col-lg-2 text-end"><?= format_price($row['booking_subtotal'], $business['currency_code']) ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                    <hr />
                                    <?php foreach ($order_detail['adjustments'] as $row) : ?>
                                        <div class="row">
                                            <div class="col-8 col-lg-9 col-xl-10 text-end"><?= $row['line_detail'] ?></div>
                                            <div class="col-4 col-lg-3 col-xl-2 text-end"><?= format_price($row['line_amount'], $business['currency_code']) ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                    <hr />
                                    <div class="row">
                                        <div class="col-8 col-lg-9 col-xl-10 text-end"><b><?= lang('Order.financial') ?></b></div>
                                        <div class="col-4 col-lg-3 col-xl-2 text-end"><?= format_price($order_detail['order_total'], $business['currency_code']) ?></div>
                                    </div>
                                    <hr />
                                    <h3><?= lang('Order.payment-history') ?></h3>
                                    <?php if (empty($order_detail['payments'])):  ?>
                                        <p class="alert bg-warning"><?= lang('Order.payment-not-found') ?></p>
                                    <?php else: ?>
                                        <?php foreach ($order_detail['payments'] as $row) : ?>
                                            <div class="row">
                                                <div class="col-6 col-md-4">
                                                    <i class="fa-regular fa-clock"></i> <span class="utc-to-local"><?= date('Y-m-d\TH:i:s', strtotime($row['created_at'])) . '+00:00' ?></span><br>
                                                    <?= $payment_statuses['payment_status'][$row['payment_status']] ?> <?= lang('OrderPayment.enum.payment_status.' . $row['payment_status']) ?>
                                                </div>
                                                <div class="col-6 col-md-4 col-lg-5 col-xl-6">
                                                    <?= lang('BusinessPaymentMethod.enum.payment_method.' . $row['payment_method']) ?>
                                                    <?= (empty($row['payment_notes']) ? '' : '<br>' . lang('OrderPayment.field.payment_notes') . ': ' . $row['payment_notes']) ?>
                                                    <?= (empty($row['staff_comment']) ? '' : '<br>' . lang('OrderPayment.field.staff_comment') . ': ' . $row['staff_comment']) ?>
                                                </div>
                                                <div class="col-12 col-md-4 col-lg-3 col-xl-2 text-end"><?= format_price($row['amount_paid'], $business['currency_code'])?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col"><hr /></div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <h4><?= lang('Order.add-payment') ?></h4>
                                    <hr />
                                    <h3><?= lang('Order.history') ?></h3>
                                    <?php if (empty($order_detail['history'])) : ?>
                                        <p class="alert bg-warning"><?= lang('Order.history-not-found') ?></p>
                                    <?php else: ?>
                                        <?php foreach ($order_detail['history'] as $row) : ?>
                                            <div class="row">
                                                <div class="col-12 col-md-4 col-lg-3">
                                                    <i class="fa-regular fa-clock"></i> <span class="utc-to-local"><?= date('Y-m-d\TH:i:s', strtotime($row['created_at'])) . '+00:00' ?></span>
                                                </div>
                                                <div class="col-12 col-md-8 col-lg-9">
                                                    <?php if ('TEXT' == $row['comment_type']) : ?>
                                                        <?= $row['comment_value'] ?>
                                                    <?php else : ?>
                                                        <?= lang('OrderComment.enum.comment_code.' . $row['comment_code']) ?> : <?= $row['comment_value'] ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    <?php endif; ?>
                                    <h4><?= lang('Order.add-comment') ?></h4>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <h3><?= lang('Order.statuses') ?></h3>
                                    <div class="row">
                                        <div class="col-6 text-end"><?= lang('OrderMaster.field.order_status') ?></div>
                                        <div class="col-6"><?= $statuses['order_status'][$order_detail['order_status']] ?> <?= lang('OrderMaster.enum.order_status.' . $order_detail['order_status']) ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-end"><?= lang('OrderMaster.field.financial_status') ?></div>
                                        <div class="col-6"><?= $statuses['financial_status'][$order_detail['financial_status']] ?> <?= lang('OrderMaster.enum.financial_status.' . $order_detail['financial_status']) ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-end"><?= lang('OrderMaster.field.shipping_status') ?></div>
                                        <div class="col-6"><?= $statuses['shipping_status'][$order_detail['shipping_status']] ?> <?= lang('OrderMaster.enum.shipping_status.' . $order_detail['shipping_status']) ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-end"><?= lang('Order.financial') ?></div>
                                        <div class="col-6"><?= format_price($order_detail['order_total'], $business['currency_code']) ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-end"><?= lang('OrderMaster.field.payment_method') ?></div>
                                        <div class="col-6"><?= lang('BusinessPaymentMethod.enum.payment_method.' . $order_detail['payment_method']) ?></div>
                                    </div>
                                    <hr />
                                    <h3><?= lang('Order.shipping-detail') ?></h3>
                                    <div class="row">
                                        <div class="col-6 text-end"><?= lang('OrderMaster.field.shipping_option') ?></div>
                                        <div class="col-6"><?= $statuses['shipping_option'][$order_detail['shipping_option']] ?> <?= lang('OrderMaster.enum.shipping_option.' . $order_detail['shipping_option']) ?></div>
                                    </div>
                                    <?php if ('SELF_COLLECTION' == $order_detail['shipping_option']) : ?>
                                        <?php
                                        $branch_names = json_decode($order_detail['branch_local_names'], true);
                                        $branch_name  = $branch_names[$lang] ?? '??';
                                        ?>
                                        <div class="row">
                                            <div class="col-6 text-end"><?= lang('OrderMaster.field.collection_branch_id') ?></div>
                                            <div class="col-6"><?= $branch_name ?></div>
                                        </div>
                                    <?php elseif ('SHIPPING' == $order_detail['shipping_option']) : ?>
                                        <?php foreach (['address_line_1', 'address_line_2', 'address_line_3', 'address_city', 'country_code', 'postal_code'] as $key) : ?>
                                            <div class="row">
                                                <div class="col-6 text-end"><?= lang('CustomerAddress.field.' . $key) ?></div>
                                                <div class="col-6"><?= $order_detail[$key] ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <hr />
                                    <h3><?= lang('Order.customer-detail') ?></h3>
                                    <?php foreach (['customer_name', 'email_address', 'telephone_number'] as $key) : ?>
                                        <p>
                                            <i class="fa-solid fa-caret-right"></i> <?= lang('CustomerMaster.field.' . $key) ?>:<br>
                                            <?= $order_detail[$key] ?>
                                        </p>
                                    <?php endforeach; ?>
                                    <hr />
                                    <h3><?= lang('Order.comment') ?></h3>
                                    <p>
                                        <i class="fa-solid fa-caret-right"></i> <?= lang('OrderMaster.field.customer_comment') ?>:<br>
                                        <?= (empty($order_detail['customer_comment']) ? '-' : $order_detail['customer_comment']) ?>
                                    </p>
<!--                                    <p> JUST DON'T USE STAFF_COMMENT ANYMORE, USE THE COMMENT TABLE -->
<!--                                        <i class="fa-solid fa-caret-right"></i> --><?php //= lang('OrderMaster.field.staff_comment') ?><!--:<br>-->
<!--                                        --><?php //= (empty($order_detail['staff_comment']) ? '-' : $order_detail['staff_comment']) ?>
<!--                                    </p>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>