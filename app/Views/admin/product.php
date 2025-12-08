<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12 col-lg-10 col-xxl-8">
            <div class="card">
                <div class="card-body p-3">
                    <pre>
                        use cases:
                        product_master, product_variant, and then, product_variant_inventory
                    </pre>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>