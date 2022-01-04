<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            <div class="card">

                <div class="card-header">
                    <i class="fas fa-unlock fa-lg me-2"></i><strong><?= lang('Auth.login.forgot_password') ?></strong></i>
                    <a href="#" target="_blank" class="float-end card-header-help-icon" title="Get help for this page..."><i class="far fa-question-circle fa-lg"></i></a>
                </div>

                <div class="card-body">

                    <?= view('CI4\Auth\Views\_alert') ?>

                    <p><?= lang('Auth.login.enter_email_instructions') ?></p>

                    <form action="<?= route_to('forgot') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="email"><?= lang('Auth.login.email_address') ?></label>
                            <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.login.email') ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.email') ?>
                            </div>
                        </div>

                        <br>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary"><?= lang('Auth.login.send_instructions') ?></button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>