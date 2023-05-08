<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

    <div class="container">

        <?= view('CI4\Auth\Views\_alert') ?>

        <div class="row">
            <div class="col-sm-6 offset-sm-3">

                <div class="card mt-5">

                    <div class="card-header">
                        <i class="bi-unlock-fill me-2"></i><strong><?= lang('Auth.2fa.login.header') ?></strong>
                        <a href="#" target="_blank" class="float-end card-header-help-icon" title="Get help for this page..."><i class="bi-question-circle"></i></a>
                    </div>

                    <div class="card-body">

                        <form action="<?= base_url() ?>/login2fa" method="post">
                            <?= csrf_field() ?>
                            <input name="hidden_username" type="hidden" value="<?= $user->username ?>">

                            <div class="mb-3">
                                <label for="pin"><?= lang('Auth.2fa.login.pin') . ' (' . $user->username . ')' ?></label>
                                <input id="pin" class="form-control text-center mt-2" name="pin" type="text" minlength="6" maxlength="6" required pattern="^[0-9]{1,6}$" autofocus>
                                <div class="invalid-feedback">
                                    <?= session('errors.pin') ?>
                                </div>
                            </div>

                            <br>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary"><?= lang('Auth.2fa.login.pin_login') ?></button>
                                <a href="<?= base_url() ?>/" class="btn btn-secondary mt-2"><?= lang('Auth.btn.cancel') ?></a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>