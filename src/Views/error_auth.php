<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

  <div class="container mb-4">

    <?= view('CI4\Auth\Views\_alert') ?>

  </div>

<?= $this->endSection() ?>
