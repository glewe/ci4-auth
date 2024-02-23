<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

  <div class="container">

    <?= view('CI4\Auth\Views\_alert') ?>

    <div class="row">
      <div class="col-sm-6 offset-sm-3">

        <div class="card">

          <div class="card-header">
            <i class="bi-unlock-fill me-2"></i><strong><?= lang('Auth.2fa.setup.header') ?></strong>
            <a href="#" target="_blank" class="float-end card-header-help-icon" title="Get help for this page..."><i class="bi-question-circle"></i></a>
          </div>

          <div class="card-body">

            <!-- Warning Alert -->
            <?php if ($has_secret) :
              echo bs5_alert($data = [
                'type' => 'warning',
                'icon' => 'exclamation',
                'title' => '',
                'subject' => '',
                'text' => lang('Auth.2fa.setup.secret_exists'),
                'help' => '',
                'dismissible' => true,
              ]);
            endif ?>

            <?php if ($config->require2FA) { ?>
              <p><?= lang('Auth.2fa.setup.2fa_required') ?></p>
            <?php } else { ?>
              <p><?= lang('Auth.2fa.setup.2fa_optional') ?></p>
            <?php } ?>
            <p><?= lang('Auth.2fa.setup.onboarding_comment') ?></p>

            <div class="row">
              <div class="col text-center">
                <img src="<?= $qrcode ?>" alt="" style="max-width:300px;"><br>
                <span class="fw-bold text-primary"><?= $secret ?></span>
              </div>
            </div>

            <p class="mt-2"><?= lang('Auth.2fa.setup.authenticator_code_desc') ?></p>

            <form action="<?= base_url() ?>/setup2fa" method="post">
              <?= csrf_field() ?>

              <input name="hidden_secret" type="hidden" value="<?= $secret ?>">
              <input name="hidden_email" type="hidden" value="<?= $user->email ?>">

              <div class="mb-3">
                <label for="authenticator_code"><?= lang('Auth.2fa.setup.authenticator_code') ?></label>
                <input id="authenticator_code" class="form-control text-center" name="authenticator_code" type="text" minlength="6" maxlength="6" required pattern="^[0-9]{1,6}$" autofocus>
                <div class="invalid-feedback">
                  <?= session('errors.authenticator_code') ?>
                </div>
              </div>

              <br>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary"><?= lang('Auth.btn.verify') ?></button>
                <a href="<?= base_url() ?>/" class="btn btn-secondary mt-2"><?= lang('Auth.btn.cancel') ?></a>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

<?= $this->endSection() ?>
