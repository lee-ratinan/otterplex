<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12 col-lg-10 col-xxl-8">
            <div class="card">
                <div class="card-body p-3">
                    <pre>
                        use cases:
                        everything for the order:
                        order_master
                        order_booking_item
                        order_booking_session
                        order_line_adjustment
                        order_line_item
                        order_payment
                        session_master
                    </pre>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>