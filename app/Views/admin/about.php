<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col col-lg-6">
                            <?php $about = lang('Admin.about'); ?>
                            <?php foreach ($about as $group) : ?>
                                <?php foreach ($group as $key => $data) : ?>
                                    <?php if ('title' == $key) : ?>
                                        <h2><?= $data ?></h2>
                                    <?php else : ?>
                                        <p><?= $data ?></p>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <hr>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection() ?>