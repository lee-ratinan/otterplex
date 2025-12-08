<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12 col-lg-10 col-xxl-8">
            <div class="card">
                <div class="card-body p-3">
                    <pre>
                        use cases:
                        - edit business itself ([business_master])
                        - show the contract and history of payments (business_contract, business_contract_payment)
                        <?php print_r($business); ?>
                        <?php print_r($business_types); ?>
                        <?php print_r($all_languages); ?>
                    </pre>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>