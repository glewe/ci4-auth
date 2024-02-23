<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

  <div class="container mb-4">

    <?= view('CI4\Auth\Views\_alert') ?>

    <div class="card">
      <?= bs5_cardheader([ 'icon' => 'bi-shield-shaded', 'title' => 'Welcome to CI4-Auth', 'help' => 'https://github.com/glewe/ci4-auth' ]) ?>
      <div class="card-body">

        <p>CI4-Auth is based on the great <a href="https://github.com/lonnieezell/myth-auth" target="_blank">Myth-Auth</a> library for Codeigniter 4.
          Due credits go to its author <a href="https://github.com/lonnieezell" target="_blank">Lonnie Ezell</a> and the team for this awesome work.</p>

        <p>I tried to bend the code here and there to meet my specific requirements but after a while I noticed that my changes got quite large and decided to build <i>CI4-Auth</i> based on Myth-Auth,
          changing and adding features I needed for my project.</p>

        <p>Please refer to the <a href="https://github.com/glewe/ci4-auth" target="_blank">CI4-Auth GitHub repo</a> for more details or to submit issues and requests.</p>

        <h3 class="my-4">Authentication</h3>

        <p>The Authentication menu (<i class="bi-person-fill"></i>) in the navbar contains links to views available based on the login status, e.g. log in, log out, register etc.</p>

        <h3 class="my-4">Authorization</h3>

        <p>The Authorization menu (<i class="bi-gear-fill"></i>) holds the links to manage users, groups, roles and permissions (dependent on the permissions of the logged in user). Use the admin login to see them all.</p>

        <h3 class="my-4">Enjoy and extend</h3>

        <p>The views provided in CI4-Auth are considered basic. They provide access to the auth objects and also show some ways to utilize Bootstrap and Bootstrap Icons.</p>
        <p>Feel free to change or extend them to your liking.</p>

        <h3 class="my-4">Report bugs</h3>

        <p>If you find bugs or ways for improvement you are welcome to submit an issue here:<br>
          <a href="https://github.com/glewe/ci4-auth/issues" target="_blank">GitHub Issue Tracker</a>
        </p>
        <hr>
        <p>Best regards,<br>
          George Lewe</p>

      </div>
      <div class="card-footer pt-3 fst-italic">
        <p>CodeIgniter <?= CodeIgniter\CodeIgniter::CI_VERSION ?><br>
          CI4-Auth <?= config('AuthInfo')->version ?><br>
          Page rendered in {elapsed_time} seconds<br>
          Environment: <?= ENVIRONMENT ?></p>
      </div>
    </div>
  </div>

<?= $this->endSection() ?>
