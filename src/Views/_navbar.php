<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">

        <a class="navbar-brand" href="#"><i class="bi-shield-shaded text-warning"></i></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLeweCI4Auth" aria-controls="navbarLeweCI4Auth" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarLeft">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?= base_url() ?>/"><i class="bi-house-fill menu-icon"></i><?= lang('Auth.nav.home') ?></a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <!--                    <a class="nav-link dropdown-toggle" href="#" id="authDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">--><?php //= lang('Auth.nav.settings') ?><!--</a>-->
                    <a class="nav-link dropdown-toggle" href="#" id="authDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi-gear-fill menu-icon"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="authDropdown">
                        <li><a class="dropdown-item" href="<?= base_url() ?>/groups"><i class="bi-people-fill menu-icon"></i><?= lang('Auth.nav.authorization.groups') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/permissions"><i class="bi-key-fill menu-icon"></i><?= lang('Auth.nav.authorization.permissions') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/roles"><i class="bi-person-circle menu-icon"></i><?= lang('Auth.nav.authorization.roles') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/users"><i class="bi-person-fill menu-icon"></i><?= lang('Auth.nav.authorization.users') ?></a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <!--                    <a class="nav-link dropdown-toggle" href="#" id="authDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">--><?php //= lang('Auth.nav.authentication.self') ?><!--</a>-->
                    <a class="nav-link dropdown-toggle" href="#" id="authDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi-person-fill menu-icon"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="authDropdown">
                        <li><a class="dropdown-item" href="<?= base_url() ?>/forgot"><i class="bi-unlock-fill menu-icon"></i><?= lang('Auth.nav.authentication.forgot_password') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/reset-password"><i class="bi-unlock-fill menu-icon"></i><?= lang('Auth.nav.authentication.reset_password') ?></a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/register"><i class="bi-person-fill-add menu-icon"></i><?= lang('Auth.nav.authentication.register') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/login"><i class="bi-box-arrow-in-right menu-icon"></i><?= lang('Auth.nav.authentication.login') ?></a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/logout"><i class="bi-box-arrow-left menu-icon"></i><?= lang('Auth.nav.authentication.logout') ?></a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>/whoami"><i class="bi-question-circle-fill menu-icon"></i><?= lang('Auth.nav.authentication.whoami') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</nav>