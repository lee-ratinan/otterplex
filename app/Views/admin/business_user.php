<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12 col-lg-10 col-xxl-8">
            <div class="card">
                <div class="card-body p-3">
                    <pre>
                        use cases:
                        user_master (add new user who is just a staff/manager > link to business_user)
                        business_user (link, unlink)
                        branch_user (link, unlink, change branch, whatever)
                    </pre>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>