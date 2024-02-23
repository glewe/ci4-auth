<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

  <div class="container">

    <?= view('CI4\Auth\Views\_alert') ?>

    <div class="card">

      <?= bs5_cardheader([ 'icon' => 'bi-person-circle', 'title' => lang('Auth.btn.editRole'), 'help' => '#' ]) ?>

      <div class="card-body">

        <form action="<?= base_url() ?>/roles/edit/<?= $role->id ?>" method="post">
          <?= csrf_field() ?>

          <?php
          echo bs5_formrow([
            'type' => 'text',
            'mandatory' => true,
            'name' => 'name',
            'title' => lang('Auth.role.name'),
            'desc' => lang('Auth.role.name_desc'),
            'errors' => session('errors.name'),
            'value' => $role->name
          ]);

          echo bs5_formrow([
            'type' => 'text',
            'mandatory' => false,
            'name' => 'description',
            'title' => lang('Auth.role.description'),
            'desc' => lang('Auth.role.description_desc'),
            'errors' => session('errors.description'),
            'value' => $role->description
          ]);

          $data = [
            'type' => 'select',
            'subtype' => 'multi',
            'name' => 'sel_permissions',
            'size' => '8',
            'mandatory' => false,
            'title' => lang('Auth.role.permissions'),
            'desc' => lang('Auth.role.permissions_desc'),
            'errors' => session('errors.permissions'),
          ];
          foreach ($permissions as $permission) {
            $data[ 'items' ][] = [
              'selected' => array_key_exists($permission->id, $rolePermissions) ? true : false,
              'title' => $permission->name,
              'value' => $permission->id,
            ];
          }
          echo bs5_formrow($data);
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
