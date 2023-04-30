<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

    <div class="container">

        <?= view('CI4\Auth\Views\_alert') ?>

        <div class="card">

            <?= bs5_cardheader(['icon' => 'bi-key-fill', 'title' => lang('Auth.btn.createPermission'), 'help' => '#']) ?>

            <div class="card-body row">

                <form action="<?= base_url() ?>/permissions/create" method="post">
                    <?= csrf_field() ?>

                    <?php
                    echo bs5_formrow([
                        'type' => 'text',
                        'mandatory' => true,
                        'name' => 'name',
                        'title' => lang('Auth.permission.name'),
                        'desc' => lang('Auth.permission.name_desc'),
                        'errors' => session('errors.name'),
                        'value' => old('name')
                    ]);

                    echo bs5_formrow([
                        'type' => 'text',
                        'mandatory' => false,
                        'name' => 'description',
                        'title' => lang('Auth.permission.description'),
                        'desc' => lang('Auth.permission.description_desc'),
                        'errors' => session('errors.description'),
                        'value' => old('description')
                    ]);
                    ?>

                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary"><?= lang('Auth.btn.submit') ?></button>
                            <a class="btn btn-secondary float-end" href="<?= base_url() ?>/permissions"><?= lang('Auth.btn.cancel') ?></a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

<?= $this->endSection() ?>