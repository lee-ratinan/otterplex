<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <h2><?= lang('Admin.pages.business-policy') . ': ' . lang('BusinessPolicy.types.' . $policy_type) ?></h2>
                    ID <?= $id ?> <br/>
                    BIZ <?= $business_id ?> <br/>
                    <?= $language_code ?> <br/>
                    <?= $policy_type ?> <br/>
                    <pre><?php print_r($policy) ?></pre>
                    <?= $languages[$language_code] ?>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>