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
                    <div class="row">
                    <?php foreach ($availableMethod as $method) : ?>
                    <div class="col-12 col-md-6">
                        <h3><?= lang('BusinessPaymentMethod.methods.' . $method) ?></h3>
                        <?php
                        echo build_form_input($method . '-id', lang('BusinessPaymentMethod.field.id'), [
                            'type'     => 'text',
                            'readonly' => 'readonly',
                        ], @$results[$method]['id']);
                        echo build_form_input($method . '-business_id', lang('BusinessPaymentMethod.field.business_id'), [
                            'type'     => 'text',
                            'readonly' => 'readonly',
                        ], $session->business['business_id']);
                        echo build_form_input($method . '-payment_method', lang('BusinessPaymentMethod.field.payment_method'), [
                            'type'     => 'text',
                            'readonly' => 'readonly',
                        ], $method);
                        if ('cash' === $method) {
                            foreach ($availableLocales as $languageCode => $languageName) {
                                echo build_form_input($method . '-payment_instruction-instruction-' . $languageCode, lang('BusinessPaymentMethod.field.payment_instructions.instruction') . ': ' . $languageName, [
                                    'type' => 'text',
                                ], @$results[$method]['payment_instruction']['instruction'][$languageCode]);
                            }
                        } else if ('bank_transfer' == $method) {
                            $bankList = retrieve_bank_list($countryCode);
                            echo build_form_input($method . '-payment_instruction-swift_code', lang('BusinessPaymentMethod.field.payment_instructions.swift_code'), [
                                'type' => 'select',
                            ], @$results[$method]['payment_instruction']['swift_code'], '', $bankList);
                            echo build_form_input($method . '-payment_instruction-account_name', lang('BusinessPaymentMethod.field.payment_instructions.account_name'), [
                                'type' => 'text',
                            ], @$results[$method]['payment_instruction']['account_name']);
                            echo build_form_input($method . '-payment_instruction-account_number', lang('BusinessPaymentMethod.field.payment_instructions.account_number'), [
                                'type' => 'text',
                            ], @$results[$method]['payment_instruction']['account_number']);
                        } else if ('promptpay_static' == $method) {
                            $id_types = [
                                'phone'  => lang('BusinessPaymentMethod.field.payment_instructions.id_types.phone'),
                                'id_num' => lang('BusinessPaymentMethod.field.payment_instructions.id_types.id_num'),
                            ];
                            echo build_form_input($method . '-payment_instruction-type', lang('BusinessPaymentMethod.field.payment_instructions.type'), [
                                'type' => 'select',
                            ], @$results[$method]['payment_instruction']['type'], '', $id_types);
                            echo build_form_input($method . '-payment_instruction-target_value', lang('BusinessPaymentMethod.field.payment_instructions.target_value'), [
                                'type' => 'text',
                            ], @$results[$method]['payment_instruction']['target_value']);
                        } else if ('external_online' == $method) {
                            foreach ($availableLocales as $languageCode => $languageName) {
                                echo build_form_input($method . '-payment_instruction-title-' . $languageCode, lang('BusinessPaymentMethod.field.payment_instructions.title') . ': ' . $languageName, [
                                    'type' => 'text',
                                ], @$results[$method]['payment_instruction']['title'][$languageCode]);
                                echo build_form_input($method . '-payment_instruction-instruction-' . $languageCode, lang('BusinessPaymentMethod.field.payment_instructions.instruction') . ': ' . $languageName, [
                                    'type' => 'text',
                                ], @$results[$method]['payment_instruction']['instruction'][$languageCode]);
                            }
                        }
                        ?>
                        <div class="text-end my-3">
                            <button class="btn btn-primary btn-save" id="btn-save-<?= $method ?>"><?= lang('System.buttons.save') ?></button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php foreach ($availableMethod as $method) : ?>
            $('#btn-save-<?= $method ?>').click(function () {
                <?php
                $fields = [$method . '-id', $method . '-business_id', $method . '-payment_method'];
                if ('cash' === $method) {
                    foreach ($availableLocales as $languageCode => $languageName) {
                        $fields[] = $method . '-payment_instruction-instruction-' . $languageCode;
                    }
                } else if ('bank_transfer' == $method) {
                    $fields[] = $method . '-payment_instruction-swift_code';
                    $fields[] = $method . '-payment_instruction-account_name';
                    $fields[] = $method . '-payment_instruction-account_number';
                } else if ('promptpay_static' == $method) {
                    $fields[] = $method . '-payment_instruction-type';
                    $fields[] = $method . '-payment_instruction-target_value';
                } else if ('external_online' == $method) {
                    foreach ($availableLocales as $languageCode => $languageName) {
                        $fields[] = $method . '-payment_instruction-title-' . $languageCode;
                        $fields[] = $method . '-payment_instruction-instruction-' . $languageCode;
                    }
                }
                gen_js_fields_checker($fields);
                ?>
            });
            <?php endforeach; ?>
        });
    </script>
<?php $this->endSection() ?>