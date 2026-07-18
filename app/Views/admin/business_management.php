<?php $this->extend('admin/_layout'); ?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col col-md-6" id="page-1">
                            <h2><?= lang('Business.business-management.export.title') ?></h2>
                            <p><?= lang('Business.business-management.export.paragraph-1') ?></p>
                            <p><?= lang('Business.business-management.export.paragraph-2', [$session->user['email_address']]) ?></p>
                            <p><?= lang('Business.business-management.export.paragraph-3') ?></p>
                            <div class="text-end">
                                <button class="btn btn-danger" id="btn-export"><i class="fa-solid fa-file-csv"></i> <?= lang('Business.business-management.export.button') ?></button>
                            </div>
                            <hr />
                            <h2><?= lang('Business.business-management.delete.title') ?></h2>
                            <p><?= lang('Business.business-management.delete.paragraph-1') ?></p>
                            <p><?= lang('Business.business-management.delete.paragraph-2') ?></p>
                            <p><?= lang('Business.business-management.delete.paragraph-3') ?></p>
                            <p><?= lang('Business.business-management.delete.paragraph-4') ?></p>
                            <div class="text-end">
                                <button class="btn btn-danger" id="btn-delete-init"><i class="fa-solid fa-trash-can"></i> <?= lang('Business.business-management.delete.button') ?></button>
                            </div>
                        </div>
                        <div class="col col-md-6" id="page-2" style="display:none;">
                            <h2><?= lang('Business.business-management.delete-confirm.title') ?></h2>
                            <p><?= lang('Business.business-management.delete-confirm.paragraph-1') ?></p>
                            <p><?= lang('Business.business-management.delete-confirm.paragraph-2') ?></p>
                            <hr />
                            <p><?= lang('Business.business-management.delete-confirm.q-1.question') ?></p>
                            <p><small><em><?= lang('Business.business-management.delete-confirm.q-1.instruction') ?></em></small></p>
                            <?php for ($i = 1; $i <= 6; $i++) : ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="q-1-opt-<?= $i ?>" id="q-1-opt-<?= $i ?>">
                                    <label class="form-check-label" for="q-1-opt-<?= $i ?>">
                                        <?= lang('Business.business-management.delete-confirm.q-1.opt-' . $i) ?>
                                    </label>
                                </div>
                            <?php endfor; ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="q-1-opt-other" id="q-1-opt-other">
                                <label class="form-check-label" for="q-1-opt-other">
                                    <?= lang('Business.business-management.delete-confirm.q-1.other') ?>
                                </label>
                                <label class="form-check-label" for="q-1-opt-other-text"></label>
                                <input type="text" class="form-control" id="q-1-opt-other-text" placeholder="<?= lang('Business.business-management.delete-confirm.q-1.other') ?>">
                            </div>
                            <hr/>
                            <?php if ('free' != $session->business['contract_plan']) : ?>
                                <p><?= lang('Business.business-management.delete-confirm.q-2.question') ?></p>
                                <p>
                                    <small><em><?= lang('Business.business-management.delete-confirm.q-2.instruction') ?></em></small>
                                </p>
                                <div class="text-end">
                                    <a href="#" target="_blank"><?= lang('Business.business-management.delete-confirm.q-2.button') ?> <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="q-2-ack" id="q-2-ack">
                                    <label class="form-check-label" for="q-2-ack">
                                        <?= lang('Business.business-management.delete-confirm.q-2.ack') ?>
                                    </label>
                                </div>
                                <hr/>
                                <p><?= lang('Business.business-management.delete-confirm.q-3.question') ?></p>
                                <p>
                                    <small><em><?= lang('Business.business-management.delete-confirm.q-3.instruction') ?></em></small>
                                </p>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="q-3-ack" id="q-3-ack">
                                    <label class="form-check-label" for="q-3-ack">
                                        <?= lang('Business.business-management.delete-confirm.q-3.ack') ?>
                                    </label>
                                </div>
                                <hr/>
                            <?php endif; ?>
                            <p><?= lang('Business.business-management.delete-confirm.q-4.question') ?></p>
                            <div class="table-responsive">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <?php for ($i = 1; $i <= 10; $i++) : ?>
                                        <td class="text-center">
                                            <input class="form-check-input ms-0" type="radio" name="q-4" id="q-4-opt-<?= $i ?>" value="q-4-opt-<?= $i ?>"><br/>
                                            <label class="form-check-label" for="q-4-opt-<?= $i ?>">
                                                <?= $i ?>
                                            </label>
                                        </td>
                                        <?php endfor; ?>
                                    </tr>
                                    <tr>
                                        <td colspan="5"><?= lang('Business.business-management.delete-confirm.q-4.at-0') ?></td>
                                        <td colspan="5" class="text-end"><?= lang('Business.business-management.delete-confirm.q-4.at-10') ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-6 text-start">
                                    <a class="btn btn-outline-primary" href="<?= base_url('admin/dashboard') ?>"><i class="fa-solid fa-angles-left"></i> <?= lang('Business.business-management.delete-confirm.buttons.cancel') ?></a>
                                </div>
                                <div class="col-6 text-end">
                                    <button class="btn btn-danger" id="btn-submit-delete-confirm"><i class="fa-solid fa-trash-can"></i> <?= lang('Business.business-management.delete-confirm.buttons.confirm') ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#btn-delete-init').click(function (e) {
                e.preventDefault();
                $('#page-1').hide();
                $('#page-2').slideDown();
            });
        });
    </script>
<?php $this->endSection() ?>