<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

  <div class="container">

    <?= view('CI4\Auth\Views\_alert') ?>

    <div class="card mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <?php if (has_permissions('users.create')) { ?>
              <a href="<?= base_url() ?>/users/create" class="btn btn-primary"><?= lang('Auth.btn.createUser') ?></a>
            <?php } ?>
          </div>
          <div class="col">
            <?= bs5_searchform(base_url() . '/users', (isset($search)) ? $search : false) ?>
          </div>
        </div>
      </div>
    </div>

    <div class="card">

      <?= bs5_cardheader([ 'icon' => 'bi-person-fill', 'title' => lang('Auth.user.users'), 'help' => '#' ]) ?>

      <div class="card-body">

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
              <?php if (has_permissions([ 'users.edit', 'users.delete' ])) { ?>
                <div class="col-lg-1 text-end"><?= lang('Auth.btn.action') ?></div>
              <?php } ?>
            </div>

            <?php foreach ($users as $user) :

              $userGroups = $user->getGroups();
              $userRoles = $user->getRoles();
              ?>

              <form name="form_<?= $user->id ?>" action="<?= base_url() ?>/users" method="post">
                <?= csrf_field() ?>

                <input name="hidden_id" type="hidden" value="<?= $user->id ?>">
                <div class="row border-bottom mb-2 pb-2">
                  <div class="col-lg-1"><?= $i++; ?></div>
                  <div class="col-lg-2"><?= $user->username ?></div>
                  <div class="col-lg-3"><?= $user->email ?></div>
                  <div class="col-lg-2">
                    <?php foreach ($userGroups as $group) :
                      echo $group . '<br>';
                    endforeach; ?>
                  </div>
                  <div class="col-lg-2">
                    <?php foreach ($userRoles as $role) :
                      echo $role . '<br>';
                    endforeach; ?>
                  </div>
                  <div class="col-lg-1">
                    <?= $user->isActivated() ?
                      '<a href="#" data-bs-toggle="tooltip" data-bs-title="' . lang('Auth.account.active') . '" data-bs-custom-class="tooltip-success"><i class="bi-check-square-fill text-success"></i></a>' :
                      '<a href="#" data-bs-toggle="tooltip" data-bs-title="' . lang('Auth.account.inactive') . '" data-bs-custom-class="tooltip-warning"><i class="bi-x-square-fill text-warning"></i></a>'
                    ?>
                    <?= $user->isBanned() ?
                      '<a href="#" data-bs-toggle="tooltip" data-bs-title="' . lang('Auth.account.banned') . '" data-bs-custom-class="tooltip-danger"><i class="bi-sign-stop-fill text-danger"></i></a>' : ''
                    ?>
                    <?= $user->hasSecret() ?
                      '<a href="#" data-bs-toggle="tooltip" data-bs-title="' . lang('Auth.account.2fa') . '" data-bs-custom-class="tooltip-warning"><i class="bi-shield-fill-check text-warning"></i></a>' : ''
                    ?>
                  </div>
                  <?php if (has_permissions([ 'users.edit', 'users.delete' ])) { ?>
                    <div class="col-lg-1 text-end">
                      <div class="btn-user">
                        <button id="action-<?= $user->id ?>" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('Auth.btn.action') ?></button>
                        <div class="dropdown-menu" aria-labelledby="action-<?= $user->id ?>">
                          <?php if (has_permissions([ 'users.edit' ])) { ?>
                            <a class="dropdown-item" href="users/edit/<?= $user->id ?>"><i class="bi-pencil-square me-2"></i><?= lang('Auth.btn.edit') ?></a>
                          <?php } ?>
                          <?php if ($user->hasSecret() && has_permissions([ 'users.edit' ])) { ?>
                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalRemoveSecret_<?= $user->id ?>"><i class="bi-shield-x me-2"></i><?= lang('Auth.btn.remove_secret') ?></button>
                          <?php } ?>
                          <?php if (has_permissions([ 'users.delete' ])) { ?>
                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalDeleteUser_<?= $user->id ?>"><i class="bi-trash me-2"></i><?= lang('Auth.btn.delete') ?></button>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                    <?php
                    if (has_permissions([ 'users.edit' ])) {
                      echo bs5_modal([
                        'id' => 'modalRemoveSecret_' . $user->id,
                        'header' => lang('Auth.modal.confirm'),
                        'header_color' => 'danger',
                        'body' => lang('Auth.user.remove_secret_confirm') . "<br><br>" . lang('Auth.user.remove_secret_confirm_desc'),
                        'btn_color' => 'danger',
                        'btn_name' => 'btn_remove_secret',
                        'btn_text' => lang('Auth.btn.remove_secret'),
                      ]);
                    }
                    if (has_permissions([ 'users.delete' ])) {
                      echo bs5_modal([
                        'id' => 'modalDeleteUser_' . $user->id,
                        'header' => lang('Auth.modal.confirm'),
                        'header_color' => 'danger',
                        'body' => lang('Auth.user.delete_confirm') . ":<br><br><ul><li><strong>" . $user->username . " (" . $user->email . ")</strong></li></ul>",
                        'btn_color' => 'danger',
                        'btn_name' => 'btn_delete',
                        'btn_text' => lang('Auth.btn.delete'),
                      ]);
                    }
                  } ?>
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
