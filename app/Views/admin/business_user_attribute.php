<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="col-12 col-md-6">
                        <h2><?= lang('Business.user-attribute.title') ?></h2>
                        <p><?= lang('Business.user-attribute.paragraph') ?></p>
                        <p><?= lang('Business.user-attribute.paragraph-2', [USER_ATTR_MAX]) ?></p>
                        <?php if (empty($attributes)) : ?>
                            <div class="alert alert-warning">
                                <i class="fa-solid fa-triangle-exclamation"></i> <?= lang('System.generic-term.no-data') ?>
                            </div>
                        <?php else : ?>
                            <?php foreach ($attributes as $attribute) : ?>
                                <div class="row">
                                    <div class="col-12">
                                        <?php
                                        $attribute_local_names = json_decode($attribute['attribute_local_names'], true);
                                        $arrayAttr = [];
                                        foreach ($languages as $lc => $ln) {
                                            $arrayAttr[] = '<small>- ' . $ln . ':</small> ' . $attribute_local_names[$lc];
                                        }
                                        ?>
                                        <p>
                                            <b>
                                                <small><?= lang('BusinessUserAttribute.field.attribute_name') ?></small><br/>
                                                <?= implode('<br/>', $arrayAttr) ?>
                                            </b>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <p>
                                            <small><?= lang('BusinessUserAttribute.field.data_type') ?></small><br/>
                                            <?= lang('BusinessUserAttribute.enum.data_type.' . $attribute['data_type']) ?>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <?php if ('num' == $attribute['data_type']) : ?>
                                            <small><?= lang('BusinessUserAttribute.field.data_unit') ?></small><br/>
                                            <?php
                                            $data_unit = json_decode($attribute['data_unit'], true);
                                            foreach ($languages as $lc => $ln) {
                                                echo '<small>- ' . $ln . ':</small> ' . $data_unit[$lc] . '<br/>';
                                            }
                                            ?>
                                        <?php elseif ('list' == $attribute['data_type']) : ?>
                                            <small><?= lang('BusinessUserAttribute.field.data_list') ?></small><br/>
                                            <?php
                                            $data_list = json_decode($attribute['data_list'], true);
                                            echo '<table class="table table-bordered table-hover table-striped table-sm">';
                                            echo '<tr><th></th>';
                                            foreach ($languages as $lc => $ln) {
                                                echo '<th>' . $ln . '</th>';
                                            }
                                            echo '</tr>';
                                            foreach ($data_list as $key => $lang_list) {
                                                echo '<tr><td>' . $key . '</td>';
                                                foreach ($languages as $lc => $ln) {
                                                    echo '<td>' . $lang_list[$lc] . '</td>';
                                                }
                                                echo '</tr>';
                                            }
                                            echo '</table>';
                                            ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-12">
                                        <small><?= lang('BusinessUserAttribute.field.in_use') ?></small><br/>
                                        <span class="in_use_value" id="in_use_value_<?= $attribute['id'] ?>">
                                            <?= ('Y' == $attribute['in_use'] ? '<i class="fa-solid fa-check-circle text-success"></i>' : '<i class="fa-solid fa-times-circle text-danger"></i>') ?>
                                            <?= lang('BusinessUserAttribute.enum.in_use.' . $attribute['in_use']) ?>
                                        </span>
                                        <button
                                            class="btn btn-outline-<?= ('Y' == $attribute['in_use'] ? 'danger' : 'success') ?> btn-sm btn-toggle-in-use"
                                            id="btn-toggle-in-use-<?= $attribute['id'] ?>"
                                            data-id="<?= $attribute['id'] ?>"
                                            data-current-value="<?= $attribute['in_use'] ?>"><?= ('Y' == $attribute['in_use'] ? lang('System.buttons.disable') : lang('System.buttons.enable')) ?></button>
                                    </div>
                                </div>
                                <hr/>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <div id="new-attribute-form" <?= (USER_ATTR_MAX <= count($attributes) ? 'class="d-none"' : '') ?>>
                            <h3><?= lang('Business.user-attribute.new-attribute') ?></h3>
                            <?php
                            foreach ($languages as $lc => $ln) {
                                echo build_form_input('attribute_local_names_' . $lc, lang('BusinessUserAttribute.field.attribute_local_names') . ' (' . $ln . ')', [
                                    'type'        => 'text',
                                    'placeholder' => lang('BusinessUserAttribute.field.attribute_local_names') . ' (' . $ln . ')'
                                ]);
                            }
                            echo build_form_input('data_type', lang('BusinessUserAttribute.field.data_type'), [
                                'type'        => 'select',
                                'placeholder' => lang('BusinessUserAttribute.field.data_type')
                            ], '', '', [
                                'num'             => lang('BusinessUserAttribute.enum.data_type.num'),
                                'translated_text' => lang('BusinessUserAttribute.enum.data_type.translated_text'),
                                'true-false'      => lang('BusinessUserAttribute.enum.data_type.true-false'),
                                'list'            => lang('BusinessUserAttribute.enum.data_type.list'),
                            ]);
                            foreach ($languages as $lc => $ln) {
                                echo build_form_input('data_list_' . $lc, lang('BusinessUserAttribute.field.data_list') . ' (' . $ln . ')', [
                                    'type'             => 'text',
                                    'placeholder'      => lang('BusinessUserAttribute.field.data_list') . ' (' . $ln . ')',
                                    'data-explanation' => lang('BusinessUserAttribute.explanation.data_list')
                                ], '', 'data-list-input');
                            }
                            foreach ($languages as $lc => $ln) {
                                echo build_form_input('data_unit_' . $lc, lang('BusinessUserAttribute.field.data_unit') . ' (' . $ln . ')', [
                                    'type'        => 'text',
                                    'placeholder' => lang('BusinessUserAttribute.field.data_unit') . ' (' . $ln . ')'
                                ], '', 'data-unit-input');
                            }
                            ?>
                            <div class="text-end">
                                <button class="btn btn-primary" id="btn-save"><?= lang('System.buttons.save') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$unit_div_id  = [];
$list_div_id  = [];
$required_fld = [];
$data_list_id = [];
$data_unit_id = [];
foreach ($languages as $lc => $ln) {
    $unit_div_id[]  = '#div-data_unit_' . $lc . '-container';
    $list_div_id[]  = '#div-data_list_' . $lc . '-container';
    $required_fld[] = 'attribute_local_names_' . $lc;
    $data_list_id[] = 'data_list_' . $lc;
    $data_unit_id[] = 'data_unit_' . $lc;
}
$div_fields = array_merge($unit_div_id, $list_div_id);
$div_str    = implode(', ', $div_fields);
$required_fld[] = 'data_type';
$all_fields = array_merge($required_fld, $data_list_id, $data_unit_id);
?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // init
            $('<?= $div_str ?>').hide();
            $('#data_type').change(function () {
                let newValue = $(this).val();
                $('<?= $div_str ?>').hide();
                if ('num' === newValue) {
                    $('<?= implode(', ', $unit_div_id) ?>').show();
                } else if ('list' === newValue) {
                    $('<?= implode(', ', $list_div_id) ?>').show();
                }
            });
            // disabled/enabled
            $('.btn-toggle-in-use').on('click', function (e) {
                e.preventDefault();
                let id = $(this).data('id'),
                    currentValue = $(this).data('current-value'),
                    newValue = ('Y' === currentValue ? 'N' : 'Y');
                $('#btn-toggle-in-use-' + id).prop('disabled', true);
                $.post(
                    "<?= base_url('/admin/business/user-attribute-manage') ?>",
                    {
                        mode: 'toggle-in-use',
                        id: id,
                        new_value: newValue,
                    },
                    function (response, status) {
                        if (response.status === "<?= STATUS_RESPONSE_OK ?>") {
                            $('#in_use_value_' + id).html(response.label);
                            $('#btn-toggle-in-use-' + id).hide();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                            $('#btn-toggle-in-use-' + id).prop('disabled', false);
                        }
                    },
                    "json"
                ).fail(function (response) {
                    let message = response.responseJSON.message ?? '<?= lang('System.response-msg.error.generic') ?>';
                    toastr.error(message);
                    $('#btn-toggle-in-use-' + id).prop('disabled', false);
                });
            });
            // save
            $('#btn-save').click(function (e) {
                e.preventDefault();
                <?php gen_js_fields_checker($required_fld); ?>
                <?php
                foreach ($data_list_id as $item) {
                    echo "let $item = $('#$item').val();\n";
                }
                foreach ($data_unit_id as $item) {
                    echo "let $item = $('#$item').val();\n";
                }
                ?>
                if ('list' === data_type) {
                    <?php
                    foreach ($data_list_id as $item) {
                        echo "if ('' === {$item}) { $('#{$item}').focus(); console.log('{$item} is empty'); return false; }\n";
                    }
                    ?>
                } else if ('num' === data_type) {
                    <?php
                    foreach ($data_unit_id as $item) {
                        echo "if ('' === {$item}) { $('#{$item}').focus(); console.log('{$item} is empty'); return false; }\n";
                    }
                    ?>
                }
                $.post(
                    "<?= base_url('/admin/business/user-attribute-manage') ?>",
                    {
                        mode: 'new-attribute',
                        <?php foreach ($all_fields as $field) : ?>
                        <?= $field ?>: <?= $field ?>,
                        <?php endforeach; ?>
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
            });
        });
    </script>
<?php $this->endSection() ?>