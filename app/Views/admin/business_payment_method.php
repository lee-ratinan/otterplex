<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <?php
        $availableLocales = get_available_locales('long');
        ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <?php foreach ($availableMethod as $method) : ?>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <h3><?= lang('BusinessPaymentMethod.methods.' . $method) ?></h3>
                                <?php
                                echo build_form_input($method . '_id', '', [
                                    'type'     => 'hidden',
                                    'readonly' => 'readonly',
                                ], $results[$method]['id'] ?? 0);
                                echo build_form_input($method . '_business_id', '', [
                                    'type'     => 'hidden',
                                    'readonly' => 'readonly',
                                ], $session->business['business_id']);
                                echo build_form_input($method . '_payment_method', '', [
                                    'type'     => 'hidden',
                                    'readonly' => 'readonly',
                                ], $method);
                                if ('cash' === $method) {
                                    foreach ($availableLocales as $languageCode => $languageName) {
                                        echo build_form_input($method . '_payment_instruction_instruction_' . $languageCode, lang('BusinessPaymentMethod.field.payment_instructions.instruction') . ': ' . $languageName, [
                                            'type' => 'text',
                                        ], @$results[$method]['payment_instruction']['instruction'][$languageCode]);
                                    }
                                } else if ('bank_transfer' == $method) {
                                    $bankList = retrieve_bank_list($countryCode);
                                    echo build_form_input($method . '-payment_instruction_swift_code', lang('BusinessPaymentMethod.field.payment_instructions.swift_code'), [
                                        'type' => 'select',
                                    ], @$results[$method]['payment_instruction']['swift_code'], '', $bankList);
                                    echo build_form_input($method . '-payment_instruction_account_name', lang('BusinessPaymentMethod.field.payment_instructions.account_name'), [
                                        'type' => 'text',
                                    ], @$results[$method]['payment_instruction']['account_name']);
                                    echo build_form_input($method . '-payment_instruction_account_number', lang('BusinessPaymentMethod.field.payment_instructions.account_number'), [
                                        'type' => 'text',
                                    ], @$results[$method]['payment_instruction']['account_number']);
                                } else if ('promptpay_static' == $method) {
                                    $id_types = [
                                        'phone'  => lang('BusinessPaymentMethod.field.payment_instructions.id_types.phone'),
                                    ];
                                    echo build_form_input($method . '_payment_instruction_type', lang('BusinessPaymentMethod.field.payment_instructions.type'), [
                                        'type' => 'select',
                                    ], @$results[$method]['payment_instruction']['type'], '', $id_types);
                                    echo build_form_input($method . '_payment_instruction_target_value', lang('BusinessPaymentMethod.field.payment_instructions.target_value'), [
                                        'type' => 'text',
                                    ], @$results[$method]['payment_instruction']['target_value']);
                                } else if ('external_online' == $method) {
                                    foreach ($availableLocales as $languageCode => $languageName) {
                                        echo build_form_input($method . '_payment_instruction_title_' . $languageCode, lang('BusinessPaymentMethod.field.payment_instructions.title') . ': ' . $languageName, [
                                            'type' => 'text',
                                        ], @$results[$method]['payment_instruction']['title'][$languageCode]);
                                        echo build_form_input($method . '_payment_instruction_instruction_' . $languageCode, lang('BusinessPaymentMethod.field.payment_instructions.instruction') . ': ' . $languageName, [
                                            'type' => 'text',
                                        ], @$results[$method]['payment_instruction']['instruction'][$languageCode]);
                                    }
                                }
                                ?>
                                <div class="text-end my-3">
                                    <?php if (isset($results[$method])) : ?>
                                        <button class="btn btn-primary btn-save" id="btn-save-<?= $method ?>"><?= lang('System.buttons.save') ?></button>
                                        <button class="btn btn-outline-danger btn-remove" id="btn-remove-<?= $method ?>"><?= lang('System.buttons.remove') ?></button>
                                        <button class="btn btn-outline-danger btn-remove d-none" id="btn-remove-confirm-<?= $method ?>" data-id="<?= $results[$method]['id'] ?>"><?= lang('System.buttons.remove-confirm') ?></button>
                                    <?php else: ?>
                                        <button class="btn btn-primary btn-save" id="btn-save-<?= $method ?>"><?= lang('System.buttons.new') ?></button>
                                    <?php endif; ?>
                                </div>
                                <hr/>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="payment_method" name="payment_method" value="" />
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php foreach ($availableMethod as $method) : ?>
            $('#btn-save-<?= $method ?>').click(function (e) {
                e.preventDefault();
                <?php
                $fields   = [];
                $fields[] = $method . '_business_id';
                $fields[] = $method . '_payment_method';
                if ('cash' === $method) {
                    foreach ($availableLocales as $languageCode => $languageName) {
                        $fields[] = $method . '_payment_instruction_instruction_' . $languageCode;
                    }
                } else if ('bank_transfer' == $method) {
                    $fields[] = $method . '_payment_instruction_swift_code';
                    $fields[] = $method . '_payment_instruction_account_name';
                    $fields[] = $method . '_payment_instruction_account_number';
                } else if ('promptpay_static' == $method) {
                    $fields[] = $method . '_payment_instruction_type';
                    $fields[] = $method . '_payment_instruction_target_value';
                } else if ('external_online' == $method) {
                    foreach ($availableLocales as $languageCode => $languageName) {
                        $fields[] = $method . '_payment_instruction_title_' . $languageCode;
                        $fields[] = $method . '_payment_instruction_instruction_' . $languageCode;
                    }
                }
                gen_js_fields_checker($fields);
                $fields[] = $method . '_id';
                ?>
                $('#btn-save-<?= $method ?>').prop('disabled', true);
                $('#payment_method').val(<?= $method ?>_payment_method);
                $.post(
                    "<?= base_url('/admin/business/payment-method') ?>",
                    <?php $fields[] = 'payment_method'; gen_json_fields_to_fields($fields) ?>,
                    function (response, status) {
                        $('#btn-save-<?= $method ?>').prop('disabled', false);
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            toastr.success(response.message);
                            setTimeout(function() { location.reload(); }, 3000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    $('#btn-save-<?= $method ?>').prop('disabled', false);
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                });
            });
            <?php endforeach; ?>
        });
    </script>
<?php $this->endSection() ?>