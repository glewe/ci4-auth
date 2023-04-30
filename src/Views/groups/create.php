<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

    <div class="container">

        <?= view('CI4\Auth\Views\_alert') ?>

        <div class="card">

            <?= bs5_cardheader(['icon' => 'bi-people-fill', 'title' => lang('Auth.btn.createGroup'), 'help' => '#']) ?>

            <div class="card-body">

                <form action="<?= base_url() ?>/groups/create" method="post">
                    <?= csrf_field() ?>

                    <?php
                    echo bs5_formrow([
                        'type' => 'text',
                        'mandatory' => true,
                        'name' => 'name',
                        'title' => lang('Auth.group.name'),
                        'desc' => lang('Auth.group.name_desc'),
                        'errors' => session('errors.name'),
                        'value' => old('name')
                    ]);

                    echo bs5_formrow([
                        'type' => 'text',
                        'mandatory' => false,
                        'name' => 'description',
                        'title' => lang('Auth.group.description'),
                        'desc' => lang('Auth.group.description_desc'),
                        'errors' => session('errors.description'),
                        'value' => old('description')
                    ]);

                    ?>

                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary"><?= lang('Auth.btn.submit') ?></button>
                            <a class="btn btn-secondary float-end" href="<?= base_url() ?>/groups"><?= lang('Auth.btn.cancel') ?></a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

<?= $this->endSection() ?>