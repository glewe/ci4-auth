<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            <div class="card">

                <div class="card-header">
                    <i class="fas fa-user-plus fa-lg me-2"></i><strong><?= lang('Auth.login.register') ?></strong></i>
                    <a href="#" target="_blank" class="float-end card-header-help-icon" title="Get help for this page..."><i class="far fa-question-circle fa-lg"></i></a>
                </div>

                <div class="card-body">

                    <?= view('CI4\Auth\Views\_alert') ?>

                    <form action="<?= base_url() ?>/register" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="email"><?= lang('Auth.login.email') ?></label>
                            <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.login.email') ?>" value="<?= old('email') ?>">
                            <small id="emailHelp" class="form-text text-muted text-italic"><?= lang('Auth.login.we_never_share') ?></small>
                        </div>

                        <div class="mb-3">
                            <label for="username"><?= lang('Auth.login.username') ?></label>
                            <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="<?= lang('Auth.login.username') ?>" value="<?= old('username') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password"><?= lang('Auth.login.password') ?></label>
                            <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.login.password') ?>" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="pass_confirm"><?= lang('Auth.login.repeat_password') ?></label>
                            <input type="password" name="pass_confirm" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.login.repeat_password') ?>" autocomplete="off">
                        </div>
                        <br>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary"><?= lang('Auth.login.register') ?></button>
                        </div>

                    </form>

                    <hr>

                    <p><?= lang('Auth.login.already_registered') ?> <a class="card-link" href="<?= base_url() ?>/login"><?= lang('Auth.login.sign_in') ?></a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>