<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-4 col-lg-2">
                            <label for="payment_method"><?= lang('OrderMaster.field.payment_method') ?></label>
                            <select class="form-control" id="payment_method">
                                <option></option>
                                <option value="cash"><?= lang('BusinessPaymentMethod.methods.cash') ?></option>
                                <option value="bank_transfer"><?= lang('BusinessPaymentMethod.methods.bank_transfer') ?></option>
                                <option value="promptpay_static"><?= lang('BusinessPaymentMethod.methods.promptpay_static') ?></option>
                                <option value="external_online"><?= lang('BusinessPaymentMethod.methods.external_online') ?></option>
                            </select>
                        </div>
                        <div class="col-4 col-lg-2">
                            <label for="order_status"><?= lang('OrderMaster.field.order_status') ?></label>
                            <select class="form-control" id="order_status">
                                <option></option>
                                <option value="OPEN"><?= lang('OrderMaster.enum.order_status.OPEN') ?></option>
                                <option value="CLOSED"><?= lang('OrderMaster.enum.order_status.CLOSED') ?></option>
                                <option value="CANCELED"><?= lang('OrderMaster.enum.order_status.CANCELED') ?></option>
                            </select>
                        </div>
                        <div class="col-4 col-lg-2">
                            <label for="financial_status"><?= lang('OrderMaster.field.financial_status') ?></label>
                            <select class="form-control" id="financial_status">
                                <option></option>
                                <option value="PENDING"><?= lang('OrderMaster.enum.financial_status.PENDING') ?></option>
                                <option value="PAID"><?= lang('OrderMaster.enum.financial_status.PAID') ?></option>
                                <option value="PARTIALLY_PAID"><?= lang('OrderMaster.enum.financial_status.PARTIALLY_PAID') ?></option>
                                <option value="REFUNDED"><?= lang('OrderMaster.enum.financial_status.REFUNDED') ?></option>
                                <option value="PARTIALLY_REFUNDED"><?= lang('OrderMaster.enum.financial_status.PARTIALLY_REFUNDED') ?></option>
                            </select>
                        </div>
                        <div class="col-4 col-lg-2">
                            <label for="shipping_option"><?= lang('OrderMaster.field.shipping_option') ?></label>
                            <select class="form-control" id="shipping_option">
                                <option></option>
                                <option value="NOT_APPLICABLE"><?= lang('OrderMaster.enum.shipping_option.NOT_APPLICABLE') ?></option>
                                <option value="SELF_COLLECTION"><?= lang('OrderMaster.enum.shipping_option.SELF_COLLECTION') ?></option>
                                <option value="SHIPPING"><?= lang('OrderMaster.enum.shipping_option.SHIPPING') ?></option>
                            </select>
                        </div>
                        <div class="col-4 col-lg-2">
                            <label for="shipping_status"><?= lang('OrderMaster.field.shipping_status') ?></label>
                            <select class="form-control" id="shipping_status">
                                <option></option>
                                <option value="OPEN"><?= lang('OrderMaster.enum.shipping_status.OPEN') ?></option>
                                <option value="IN_PROGRESS"><?= lang('OrderMaster.enum.shipping_status.IN_PROGRESS') ?></option>
                                <option value="SHIPPED"><?= lang('OrderMaster.enum.shipping_status.SHIPPED') ?></option>
                                <option value="RETURNED"><?= lang('OrderMaster.enum.shipping_status.RETURNED') ?></option>
                                <option value="NOT_APPLICABLE"><?= lang('OrderMaster.enum.shipping_status.NOT_APPLICABLE') ?></option>
                            </select>
                        </div>
                        <div class="col-4 col-lg-2">
                            <br>
                            <button class="btn btn-primary w-100" id="btn-filter">
                                <?= lang('System.buttons.filter') ?>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('OrderMaster.field.order_number') ?></th>
                                <th><?= lang('CustomerMaster.field.customer_name') ?></th>
                                <th><?= lang('OrderMaster.field.order_total') ?></th>
                                <th><?= lang('OrderMaster.field.shipping_option') ?></th>
                                <th><?= lang('OrderMaster.field.payment_method') ?></th>
                                <th><?= lang('OrderMaster.field.order_status') ?></th>
                                <th><?= lang('OrderMaster.field.financial_status') ?></th>
                                <th><?= lang('OrderMaster.field.shipping_status') ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = $('table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader: true,
                searching: true,
                ordering: true,
                <?php if ('en' != $lang) : ?>
                language: {url: '<?= base_url('/assets/vendor/DataTables/i18n/' . $lang . '.json') ?>',},
                <?php endif; ?>
                ajax: {
                    url: '<?= base_url('/admin/order') ?>',
                    type: 'POST',
                    data: function (data) {
                        data.payment_method = $('#payment_method').val();
                        data.order_status = $('#order_status').val();
                        data.financial_status = $('#financial_status').val();
                        data.shipping_option = $('#shipping_option').val();
                        data.shipping_status = $('#shipping_status').val();
                    }
                }
            });
            $('#btn-filter').click(function (e) {
                e.preventDefault();
                table.draw();
            });
        });
    </script>
<?php $this->endSection() ?>