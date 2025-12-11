<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <pre>
                        <?php print_r($user) ?>
                        <?php print_r($businessUser) ?>
                        <?php print_r($branchUser) ?>
                        <?= $mode ?>


                        <?= lang('Business.user-management.new-user') ?>
                    </pre>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>