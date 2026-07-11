<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= lang('BusinessPolicy.title') ?></h2>
                    <p><?= lang('BusinessPolicy.paragraph') ?></p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th></th>
                                <?php foreach ($languages as $language) : ?>
                                    <th><?= $language ?></th>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($policies as $policy_type_key => $policy_type_rows) : ?>
                                <tr>
                                    <td><?= lang('BusinessPolicy.types.' . $policy_type_key) ?></td>
                                    <?php foreach ($languages as $language_key => $language) : ?>
                                        <td>
                                            <?php if (empty($policy_type_rows[$language_key])) : ?>
                                                <a class="btn btn-outline-primary btn-sm" href="<?= base_url('admin/business/policy/' . $policy_type_key . '/' . $language_key . '/new') ?>">
                                                    <?= lang('System.buttons.new') ?>
                                                </a>
                                            <?php else: ?>
                                                <?php
                                                $text = strip_tags($policy_type_rows[$language_key]['policy_text_html']);
                                                $text = str_replace('&nbsp;', ' ', $text);
                                                echo mb_strlen($text) > 20 ? mb_substr($text, 0, 20) . '...' : $text;
                                                ?>
                                                <a class="btn btn-outline-primary btn-sm float-end" href="<?= base_url('admin/business/policy/' . $policy_type_rows[$language_key]['id']) ?>">
                                                    <?= lang('System.buttons.edit') ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
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