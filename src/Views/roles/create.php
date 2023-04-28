<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

    <div class="container">

        <?= view('CI4\Auth\Views\_alert') ?>

        <div class="card">

            <?= bs5_cardheader(['icon' => 'fas fa-user-circle', 'title' => lang('Auth.btn.createRole'), 'help' => '#']) ?>

            <div class="card-body">

                <form action="<?= base_url() ?>/roles/create" method="post">
                    <?= csrf_field() ?>

                    <?php
                    echo bs5_formrow([
                        'type' => 'text',
                        'mandatory' => true,
                        'name' => 'name',
                        'title' => lang('Auth.role.name'),
                        'desc' => lang('Auth.role.name_desc'),
                        'errors' => session('errors.name'),
                        'value' => old('name')
                    ]);

                    echo bs5_formrow([
                        'type' => 'text',
                        'mandatory' => false,
                        'name' => 'description',
                        'title' => lang('Auth.role.description'),
                        'desc' => lang('Auth.role.description_desc'),
                        'errors' => session('errors.description'),
                        'value' => old('description')
                    ]);
                    ?>

                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary"><?= lang('Auth.btn.submit') ?></button>
                            <a class="btn btn-secondary float-end" href="<?= base_url() ?>/roles"><?= lang('Auth.btn.cancel') ?></a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

<?= $this->endSection() ?>