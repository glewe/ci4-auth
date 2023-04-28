<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

    <div class="container">

        <?= view('CI4\Auth\Views\_alert') ?>

        <div class="row">
            <div class="col-sm-6 offset-sm-3">

                <div class="card">

                    <div class="card-header">
                        <i class="fas fa-unlock fa-lg me-2"></i><strong><?= lang('Auth.login.reset_your_password') ?></strong></i>
                        <a href="#" target="_blank" class="float-end card-header-help-icon" title="Get help for this page..."><i class="far fa-question-circle fa-lg"></i></a>
                    </div>

                    <div class="card-body">
                        <p><?= lang('Auth.login.enter_code_email_password') ?></p>

                        <form action="<?= base_url() ?>/reset-password" method="post">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="token"><?= lang('Auth.login.token') ?></label>
                                <input type="text" class="form-control <?php if (session('errors.token')) : ?>is-invalid<?php endif ?>" name="token" placeholder="<?= lang('Auth.login.token') ?>" value="<?= old('token', $token ?? '') ?>">
                                <div class="invalid-feedback">
                                    <?= session('errors.token') ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email"><?= lang('Auth.login.email') ?></label>
                                <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.login.email') ?>" value="<?= old('email') ?>">
                                <div class="invalid-feedback">
                                    <?= session('errors.email') ?>
                                </div>
                            </div>

                            <br>

                            <div class="mb-3">
                                <label for="password"><?= lang('Auth.login.new_password') ?></label>
                                <input type="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" name="password">
                                <div class="invalid-feedback">
                                    <?= session('errors.password') ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="pass_confirm"><?= lang('Auth.login.new_password_repeat') ?></label>
                                <input type="password" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" name="pass_confirm">
                                <div class="invalid-feedback">
                                    <?= session('errors.pass_confirm') ?>
                                </div>
                            </div>

                            <br>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.login.reset_password') ?></button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>