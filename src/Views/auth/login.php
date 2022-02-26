<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            <div class="card">

                <div class="card-header">
                    <i class="fas fa-sign-in-alt fa-lg me-2"></i><strong><?= lang('Auth.login.title') ?></strong></i>
                    <a href="#" target="_blank" class="float-end card-header-help-icon" title="Get help for this page..."><i class="far fa-question-circle fa-lg"></i></a>
                </div>

                <div class="card-body">

                    <?= view('CI4\Auth\Views\_alert') ?>

                    <form action="<?= base_url() ?>/login" method="post">
                        <?= csrf_field() ?>

                        <?php if ($config->validFields === ['email']) : ?>
                            <div class="mb-3">
                                <label for="login"><?= lang('Auth.login.email') ?></label>
                                <input type="email" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.login.email') ?>">
                                <div class="invalid-feedback">
                                    <?= session('errors.login') ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="mb-3">
                                <label for="login"><?= lang('Auth.login.email_or_username') ?></label>
                                <input type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.login.email_or_username') ?>">
                                <div class="invalid-feedback">
                                    <?= session('errors.login') ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="password"><?= lang('Auth.login.password') ?></label>
                            <input type="password" name="password" class="form-control  <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.login.password') ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.password') ?>
                            </div>
                        </div>

                        <?php if ($config->allowRemembering) : ?>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')) : ?> checked <?php endif ?>>
                                    <?= lang('Auth.login.remember_me') ?>
                                </label>
                            </div>
                        <?php endif; ?>

                        <br>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary"><?= lang('Auth.login.action') ?></button>
                        </div>
                    </form>

                    <hr>

                    <?php if ($config->allowRegistration) : ?>
                        <p><a href="<?= base_url() ?>/register"><?= lang('Auth.login.need_an_account') ?></a></p>
                    <?php endif; ?>

                    <?php if ($config->activeResetter) : ?>
                        <p><a href="<?= base_url() ?>/forgot"><?= lang('Auth.login.forgot_your_password') ?></a></p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>