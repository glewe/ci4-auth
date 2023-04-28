<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

    <div class="container">

        <?= view('CI4\Auth\Views\_alert') ?>
        <?= auth_display() ?>

    </div>

<?= $this->endSection() ?>