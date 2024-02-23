<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

  <div class="container">

    <?= view('CI4\Auth\Views\_alert') ?>

    <div class="card mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <?php if (has_permissions('users.create')) { ?>
              <a href="<?= base_url() ?>/groups/create" class="btn btn-primary"><?= lang('Auth.btn.createGroup') ?></a>
            <?php } ?>
          </div>
          <div class="col">
            <?= bs5_searchform(base_url() . '/groups', (isset($search)) ? $search : false) ?>
          </div>
        </div>
      </div>
    </div>

    <div class="card">

      <?= bs5_cardheader([ 'icon' => 'bi-people-fill', 'title' => lang('Auth.group.groups'), 'help' => '#' ]) ?>

      <div class="card-body">

        <?php if (!empty($groups) && is_array($groups)) :
          $i = 1; ?>

          <div class="rowcol-lg-12">

            <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight:bold;">
              <div class="col col-lg-1">#</div>
              <div class="col col-lg-3"><?= lang('Auth.name') ?></div>
              <div class="col col-lg-4"><?= lang('Auth.description') ?></div>
              <div class="col-lg-3"><?= lang('Auth.permission.permissions') ?></div>
              <?php if (has_permissions([ 'users.edit', 'users.delete' ])) { ?>
                <div class="col col-lg-1 text-end"><?= lang('Auth.btn.action') ?></div>
              <?php } ?>
            </div>

            <?php foreach ($groups as $group) : ?>

              <form name="form_<?= $group->id ?>" action="<?= base_url() ?>/groups" method="post">
                <?= csrf_field() ?>

                <input name="hidden_id" type="hidden" value="<?= $group->id ?>">
                <div class="row border-bottom mb-2 pb-2">
                  <div class="col col-lg-1"><?= $i++; ?></div>
                  <div class="col col-lg-3"><?= $group->name ?></div>
                  <div class="col col-lg-4"><?= $group->description ?></div>
                  <div class="col-lg-3">
                    <?php
                    $perms = $groupPermissions[ $group->id ];
                    foreach ($perms[ 0 ] as $perm) :
                      echo $perm->name . '<br>';
                    endforeach; ?>
                  </div>
                  <?php if (has_permissions([ 'users.edit', 'users.delete' ])) { ?>
                    <div class="col col-lg-1 text-end">
                      <div>
                        <button id="action-<?= $group->id ?>" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('Auth.btn.action') ?></button>
                        <div class="dropdown-menu" aria-labelledby="action-<?= $group->id ?>">
                          <?php if (has_permissions('users.edit')) { ?>
                            <a class="dropdown-item" href="groups/edit/<?= $group->id ?>"><i class="bi-pencil-square me-2"></i><?= lang('Auth.btn.edit') ?></a>
                          <?php } ?>
                          <?php if (has_permissions('users.delete')) { ?>
                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalDeleteGroup_<?= $group->id ?>"><i class="bi-trash me-2"></i><?= lang('Auth.btn.delete') ?></button>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                    <?php
                    if (has_permissions('users.delete')) {
                      echo bs5_modal([
                        'id' => 'modalDeleteGroup_' . $group->id,
                        'header' => lang('Auth.modal.confirm'),
                        'header_color' => 'danger',
                        'body' => lang('Auth.group.delete_confirm') . ":<br><br><ul><li><strong>" . $group->name . "</strong></li></ul>",
                        'btn_color' => 'danger',
                        'btn_name' => 'btn_delete',
                        'btn_text' => lang('Auth.btn.delete'),
                      ]);
                    }
                    ?>
                  <?php } ?>
                </div>
              </form>

            <?php endforeach; ?>

          </div>

        <?php else : ?>

          <div class="alert alert-warning" role="alert">
            <?= lang('Auth.group.none_found') ?>
          </div>

        <?php endif ?>

      </div>
    </div>
  </div>

<?= $this->endSection() ?>
