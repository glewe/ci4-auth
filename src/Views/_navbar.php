<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">

        <a class="navbar-brand" href="#"><i class="fas fa-shield-alt fa-lg text-warning"></i></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLeweAuth" aria-controls="navbarLeweAuth" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarLeweAuth">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?= base_url() ?>/"><?= lang('Auth.nav.home') ?></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="authDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('Auth.nav.authorization.self') ?></a>
                    <ul class="dropdown-menu" aria-labelledby="authDropdown">
                        <li><a class="dropdown-item" href="<?= base_url() ?>/groups"><i class="fas fa-users fa-md text-default fa-menu"></i><?= lang('Auth.nav.authorization.groups') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/permissions"><i class="fas fa-lock fa-md text-default fa-menu"></i><?= lang('Auth.nav.authorization.permissions') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/roles"><i class="fas fa-user-circle fa-md text-default fa-menu"></i><?= lang('Auth.nav.authorization.roles') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/users"><i class="fas fa-user fa-md text-default fa-menu"></i><?= lang('Auth.nav.authorization.users') ?></a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="authDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('Auth.nav.authentication.self') ?></a>
                    <ul class="dropdown-menu" aria-labelledby="authDropdown">
                        <li><a class="dropdown-item" href="<?= base_url() ?>/forgot"><i class="fas fa-unlock fa-md text-default fa-menu"></i><?= lang('Auth.nav.authentication.forgot_password') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/reset-password"><i class="fas fa-unlock fa-md text-default fa-menu"></i><?= lang('Auth.nav.authentication.reset_password') ?></a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/register"><i class="fas fa-user-plus fa-md text-default fa-menu"></i><?= lang('Auth.nav.authentication.register') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/login"><i class="fas fa-sign-in-alt fa-md text-default fa-menu"></i><?= lang('Auth.nav.authentication.login') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/logout"><i class="fas fa-sign-out-alt fa-md text-default fa-menu"></i><?= lang('Auth.nav.authentication.logout') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</nav>