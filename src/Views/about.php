<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container mb-4">

  <?= view('CI4\Auth\Views\_alert') ?>

  <div class="card mb-3">
    <?= bs5_cardheader([ 'icon' => 'bi-question-circle-fill', 'title' => lang('Auth.about.about') . ' ' . config('AuthInfo')->name, 'help' => 'https://github.com/glewe/ci4-auth' ]) ?>
    <div class="card-body row">
      <div class="col-lg-3 align-self-center text-center">
        <i class="bi-shield-shaded text-warning" style="font-size:160px;"></i>
      </div>
      <div class="col-lg-9 align-self-center">
        <h4><?= config('AuthInfo')->name ?></h4>
        <p>
          <strong><?= lang('Auth.about.version') ?>:</strong>&nbsp;&nbsp;<?= config('AuthInfo')->version ?><br>
          <strong><?= lang('Auth.about.copyright') ?>:</strong>&nbsp;&nbsp;&copy;&nbsp;<?= config('AuthInfo')->firstYear . '-' . date('Y') ?> by <a class="about" href="http://www.lewe.com/" target="_blank"><?= config('AuthInfo')->author ?></a><br>
          <strong><?= lang('Auth.about.support') ?>:</strong>&nbsp;&nbsp;<a class="about" href="https://github.com/glewe/ci4-auth/issues" target="_blank">GitHub Issues</a><br>
          <strong><?= lang('Auth.about.documentation') ?>:</strong>&nbsp;&nbsp;<a class="about" href="https://github.com/glewe/ci4-auth" target="_blank">GitHub Readme</a><br>
        </p>
      </div>
    </div>
  </div>

  <?php if (config('Auth')->showCredits) { ?>
    <div class="card mb-3">
      <div class="card-header cursor-pointer">
        <a class="text-dark" data-bs-toggle="collapse" href="#credits" role="button" aria-expanded="false" aria-controls="credits">
          <div class="row">
            <div class="col-lg-12">
              <i class="bi-hand-thumbs-up-fill me-3"></i><?= lang('Auth.about.credits') ?>
            </div>
          </div>
        </a>
      </div>
      <div class="collapse" id="credits">
        <div class="card-body">
          <ul>
            <li>CodeIgniter Team <?= lang('Auth.for') ?> <a href="https://codeigniter.com/" target="_blank">CodeIgniter</a> <?= CodeIgniter\CodeIgniter::CI_VERSION ?></li>
            <li>Lonnie Ezell <?= lang('Auth.for') ?> <a href="https://github.com/lonnieezell/myth-auth" target="_blank">Myth:Auth</a></li>
            <li>RobThree <?= lang('Auth.for') ?> <a href="https://github.com/RobThree/TwoFactorAuth" target="_blank">TwoFactorAuth</a></li>
            <li>Bootstrap Team <?= lang('Auth.for') ?> <a href="http://getbootstrap.com/" target="_blank">Bootstrap Framework and Bootstrap Icons</a></li>
            <li>Google Team <?= lang('Auth.for') ?> <a href="https://www.google.com/fonts/" target="_blank">Google Fonts</a></li>
          </ul>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php if (config('Auth')->showReleaseInfo) { ?>
    <div class="card mb-3">
      <div class="card-header cursor-pointer">
        <a class="text-dark" data-bs-toggle="collapse" href="#releaseInformation" role="button" aria-expanded="false" aria-controls="releaseInformation">
          <div class="row">
            <div class="col-lg-12">
              <i class="bi-exclamation-circle-fill me-3"></i><?= lang('Auth.about.release_info') ?>
            </div>
          </div>
        </a>
      </div>
      <div class="collapse" id="releaseInformation">
        <div class="card-body">
          <?php require('_releaseinfo.phtml'); ?>
        </div>
      </div>
    </div>
  <?php } ?>

</div>

<?= $this->endSection() ?>
