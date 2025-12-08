<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12 col-lg-10 col-xxl-8">
            <div class="card">
                <div class="card-body p-3">
                    <pre>
                        use cases:
                        branch_master (new, edit, remove, etc)
                        branch_off_dates, branch_opening_hours (add, edit, remove)
                    </pre>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>