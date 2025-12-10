<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12 col-lg-10 col-xxl-8">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= lang('Business.packages.pick-one') ?></h2>
                    <div class="row">
                        <div class="col-6">
                            <h6><?= lang('Business.packages.validity.month') ?></h6>
                            <?php foreach ($packages['month'] as $package) : ?>
                                <a class="btn btn-outline-primary w-100 mb-3 btn-package"
                                   data-package-id="<?= $package['id'] ?>"
                                   data-start-date="<?= $package['package_start_date'] ?>"
                                   data-expiry-date="<?= $package['package_expiry_date'] ?>"
                                   data-price="<?= $package['package_price'] ?>"
                                ><?= $package['package_name'] ?> : <?= format_price($package['package_price'], 'TH') ?></a>
                            <?php endforeach; ?>
                        </div>
                        <div class="col-6">
                            <h6><?= lang('Business.packages.validity.year') ?></h6>
                            <?php foreach ($packages['year'] as $package) : ?>
                                <a class="btn btn-outline-primary w-100 mb-3 btn-package"
                                   data-package-id="<?= $package['id'] ?>"
                                   data-start-date="<?= $package['package_start_date'] ?>"
                                   data-expiry-date="<?= $package['package_expiry_date'] ?>"
                                   data-price="<?= $package['package_price'] ?>"
                                ><?= $package['package_name'] ?> : <?= format_price($package['package_price'], 'TH') ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <label class="d-none" for="package_id"><input type="text" id="package_id" name="package_id" value="" /></label>
                    <?php
                    echo build_form_input('contract_start', lang('BusinessContract.field.contract_start'), [
                        'type' => 'date'
                    ]);
                    echo build_form_input('contract_expiry', lang('BusinessContract.field.contract_expiry'), [
                        'type' => 'date'
                    ]);
                    echo build_form_input('total_amount', lang('BusinessContract.field.total_amount'), [
                        'type' => 'text'
                    ]);
                    ?>
                    <div class="text-end">
                        <button class="btn btn-primary" id="btn-renew"><?= lang('Business.contract-renew') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('.btn-package').click(function (e) {
                e.preventDefault();
                $('#package_id').val($(this).data('package-id'));
                $('#contract_start').val($(this).data('start-date'));
                $('#contract_expiry').val($(this).data('expiry-date'));
                $('#total_amount').val($(this).data('price'));
            });
            $('#btn-renew').click(function (e) {
                e.preventDefault();
                <?php
                gen_js_fields_checker(['contract_start', 'contract_expiry', 'total_amount', 'package_id']);
                ?>
                $('#btn-renew').prop('disabled', true);
                $.post(
                    "<?= base_url('/admin/business/contract-renewal') ?>",
                    <?php gen_json_fields_to_fields(['contract_start', 'contract_expiry', 'total_amount', 'package_id']) ?>,
                    function (response, status) {
                        $('#btn-renew').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-renew').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
        });
    </script>
<?php $this->endSection() ?>