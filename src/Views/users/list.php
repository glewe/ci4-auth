<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container">

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <a href="<?= base_url() ?>/users/create" class="btn btn-primary"><?= lang('Auth.btn.createUser') ?></a>
                </div>
                <div class="col">
                    <?= bs5_searchform(base_url() . '/users', (isset($search)) ? $search : false) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">

        <?= bs5_cardheader(['icon' => 'fas fa-user', 'title' => lang('Auth.user.users'), 'help' => '#']) ?>

        <div class="card-body">

            <?= view('CI4\Auth\Views\_alert') ?>

            <?php if (!empty($users) && is_array($users)) :
                $i = 1; ?>

                <div class="col-lg-12">

                    <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight:bold;">
                        <div class="col-lg-1">#</div>
                        <div class="col-lg-2"><?= lang('Auth.login.username') ?></div>
                        <div class="col-lg-3"><?= lang('Auth.login.email') ?></div>
                        <div class="col-lg-2"><?= lang('Auth.group.groups') ?></div>
                        <div class="col-lg-2"><?= lang('Auth.role.roles') ?></div>
                        <div class="col-lg-1"><?= lang('Auth.user.status') ?></div>
                        <div class="col-lg-1 text-end"><?= lang('Auth.btn.action') ?></div>
                    </div>

                    <?php foreach ($users as $user) : ?>

                        <form name="form_<?= $user->id ?>" action="<?= base_url() ?>/users" method="post">
                            <?= csrf_field() ?>

                            <input name="hidden_id" type="hidden" value="<?= $user->id ?>">
                            <div class="row border-bottom mb-2 pb-2">
                                <div class="col-lg-1"><?= $i++; ?></div>
                                <div class="col-lg-2"><?= $user->username ?></div>
                                <div class="col-lg-3"><?= $user->email ?></div>
                                <div class="col-lg-2">...</div>
                                <div class="col-lg-2">...</div>
                                <div class="col-lg-1">...</div>
                                <div class="col-lg-1 text-end">
                                    <div class="btn-user">
                                        <button id="action-<?= $user->id ?>" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('Auth.btn.action') ?></button>
                                        <div class="dropdown-menu" aria-labelledby="action-<?= $user->id ?>">
                                            <a class="dropdown-item" href="users/edit/<?= $user->id ?>"><i class="far fa-edit fa-sm me-2"></i><?= lang('Auth.btn.edit') ?></a>
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalDeleteUser_<?= $user->id ?>"><i class="far fa-trash-alt fa-sm me-2"></i><?= lang('Auth.btn.delete') ?></button>
                                        </div>
                                    </div>
                                </div>
                                <?php echo bs5_modal([
                                    'id'           => 'modalDeleteUser_' . $user->id,
                                    'header'       => lang('Auth.modal.confirm'),
                                    'header_color' => 'danger',
                                    'body'         => lang('Auth.user.delete_confirm') . ":<br><br><ul><li><strong>" . $user->username . " (" . $user->email . ")</strong></li></ul>",
                                    'btn_color'    => 'danger',
                                    'btn_name'     => 'btn_delete',
                                    'btn_text'     => lang('Auth.btn.delete'),
                                ]); ?>
                            </div>
                        </form>

                    <?php endforeach; ?>

                </div>

            <?php else : ?>

                <div class="alert alert-warning" role="alert">
                    <?= lang('Auth.user.none_found') ?>
                </div>

            <?php endif ?>

        </div>
    </div>
</div>

<?= $this->endSection() ?>