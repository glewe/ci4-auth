<?= $this->extend(config('Auth')->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container">

    <?= view('CI4\Auth\Views\_alert') ?>

    <div class="card">

        <?= bs5_cardheader(['icon' => 'bi-person-fill-gear', 'title' => lang('Auth.btn.editUser'), 'help' => '#']) ?>

        <div class="card-body">

            <form action="<?= base_url() ?>/users/edit/<?= $user->id ?>" method="post">
                <?= csrf_field() ?>

                <div class="card">

                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account-tab-pane" type="button" role="tab" aria-controls="account-tab-pane" aria-selected="true"><?= lang('Auth.user.tab_account') ?></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="groups-tab" data-bs-toggle="tab" data-bs-target="#groups-tab-pane" type="button" role="tab" aria-controls="groups-tab-pane" aria-selected="false"><?= lang('Auth.user.tab_groups') ?></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles-tab-pane" type="button" role="tab" aria-controls="roles-tab-pane" aria-selected="false"><?= lang('Auth.user.tab_roles') ?></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions-tab-pane" type="button" role="tab" aria-controls="permissions-tab-pane" aria-selected="false"><?= lang('Auth.user.tab_permissions') ?></button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="account-tab-pane" role="tabpanel" aria-labelledby="account-tab" tabindex="0">
                                <div class="card">
                                    <div class="card-body">
                                        <?php
                                        echo bs5_formrow([
                                            'type' => 'email',
                                            'mandatory' => true,
                                            'name' => 'email',
                                            'title' => lang('Auth.user.email'),
                                            'desc' => lang('Auth.user.email_desc'),
                                            'errors' => session('errors.email'),
                                            'value' => $user->email
                                        ]);

                                        echo bs5_formrow([
                                            'type' => 'text',
                                            'mandatory' => true,
                                            'name' => 'username',
                                            'title' => lang('Auth.user.username'),
                                            'desc' => lang('Auth.user.username_desc'),
                                            'errors' => session('errors.username'),
                                            'value' => $user->username
                                        ]);

                                        echo bs5_formrow([
                                            'type' => 'text',
                                            'mandatory' => false,
                                            'name' => 'firstname',
                                            'title' => lang('Auth.user.firstname'),
                                            'desc' => lang('Auth.user.firstname_desc'),
                                            'errors' => session('errors.firstname'),
                                            'value' => $user->firstname
                                        ]);

                                        echo bs5_formrow([
                                            'type' => 'text',
                                            'mandatory' => false,
                                            'name' => 'lastname',
                                            'title' => lang('Auth.user.lastname'),
                                            'desc' => lang('Auth.user.lastname_desc'),
                                            'errors' => session('errors.lastname'),
                                            'value' => $user->lastname
                                        ]);

                                        echo bs5_formrow([
                                            'type' => 'text',
                                            'mandatory' => false,
                                            'name' => 'displayname',
                                            'title' => lang('Auth.user.displayname'),
                                            'desc' => lang('Auth.user.displayname_desc'),
                                            'errors' => session('errors.displayname'),
                                            'value' => $user->displayname
                                        ]);

                                        echo bs5_formrow([
                                            'type' => 'password',
                                            'mandatory' => false,
                                            'name' => 'password',
                                            'title' => lang('Auth.user.password'),
                                            'desc' => lang('Auth.user.password_desc'),
                                            'errors' => session('errors.password'),
                                        ]);

                                        echo bs5_formrow([
                                            'type' => 'password',
                                            'mandatory' => false,
                                            'name' => 'pass_confirm',
                                            'title' => lang('Auth.user.pass_confirm'),
                                            'desc' => lang('Auth.user.pass_confirm_desc'),
                                            'errors' => session('errors.pass_confirm'),
                                        ]);

                                        echo bs5_formrow([
                                            'type' => 'switch',
                                            'mandatory' => false,
                                            'name' => 'active',
                                            'title' => lang('Auth.user.active'),
                                            'desc' => lang('Auth.user.active_desc'),
                                            'errors' => session('errors.active'),
                                            'value' => $user->active,
                                            'disabled' => ($user->id == 1) ? true : false
                                        ]);

                                        echo bs5_formrow([
                                            'type' => 'switch',
                                            'mandatory' => false,
                                            'name' => 'banned',
                                            'title' => lang('Auth.user.banned'),
                                            'desc' => lang('Auth.user.banned_desc'),
                                            'errors' => session('errors.banned'),
                                            'value' => ($user->status == 'banned') ? true : false,
                                            'disabled' => ($user->id == 1) ? true : false
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="groups-tab-pane" role="tabpanel" aria-labelledby="groups-tab" tabindex="0">
                                <div class="card">
                                    <div class="card-body">
                                        <?php
                                        $data = [
                                            'type' => 'select',
                                            'subtype' => 'multi',
                                            'name' => 'sel_groups',
                                            'size' => '8',
                                            'mandatory' => false,
                                            'title' => lang('Auth.user.groups'),
                                            'desc' => lang('Auth.user.groups_desc'),
                                            'errors' => session('errors.groups'),
                                        ];
                                        foreach ($groups as $group) {
                                            $data['items'][] = [
                                                'selected' => $auth->inGroup($group->id, $user->id) ? true : false,
                                                'title' => $group->name,
                                                'value' => $group->id,
                                            ];
                                        }
                                        echo bs5_formrow($data);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="roles-tab-pane" role="tabpanel" aria-labelledby="roles-tab" tabindex="0">
                                <div class="card">
                                    <div class="card-body">
                                        <?php
                                        $data = [
                                            'type' => 'select',
                                            'subtype' => 'multi',
                                            'name' => 'sel_roles',
                                            'size' => '8',
                                            'mandatory' => false,
                                            'title' => lang('Auth.user.roles'),
                                            'desc' => lang('Auth.user.roles_desc'),
                                            'errors' => session('errors.roles'),
                                        ];
                                        foreach ($roles as $role) {
                                            $data['items'][] = [
                                                'selected' => $auth->inRole($role->id, $user->id) ? true : false,
                                                'title' => $role->name,
                                                'value' => $role->id,
                                            ];
                                        }
                                        echo bs5_formrow($data);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="permissions-tab-pane" role="tabpanel" aria-labelledby="permissions-tab" tabindex="0">
                                <div class="card">
                                    <div class="card-body">
                                        <?php
                                        $data = [
                                            'type' => 'select',
                                            'subtype' => 'multi',
                                            'name' => 'sel_permissions',
                                            'size' => '8',
                                            'mandatory' => false,
                                            'title' => lang('Auth.user.permissions'),
                                            'desc' => lang('Auth.user.permissions_desc'),
                                            'errors' => session('errors.permissions'),
                                        ];
                                        foreach ($permissions as $permission) {
                                            if (array_key_exists($permission->id, $userPersonalPermissions)) {
                                                $data['items'][] = [
                                                    'selected' => true,
                                                    'title' => $permission->name,
                                                    'value' => $permission->id,
                                                ];
                                            }
                                        }
                                        echo bs5_formrow($data);

                                        $data = [
                                            'type' => 'select',
                                            'subtype' => 'multi',
                                            'name' => 'sel_all_permissions',
                                            'size' => '8',
                                            'mandatory' => false,
                                            'title' => lang('Auth.user.permissions_all'),
                                            'desc' => lang('Auth.user.permissions_all_desc') . '<br><span class="fst-italic text-primary">' . lang('Auth.no_selection') . '</span>',
                                            'errors' => '',
                                        ];
                                        foreach ($permissions as $permission) {
                                            if ($auth->hasPermission($permission->id, $user->id)) {
                                                $data['items'][] = [
                                                    'selected' => false,
                                                    'title' => $permission->name,
                                                    'value' => $permission->id,
                                                ];
                                            }
                                        }
                                        echo bs5_formrow($data);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary"><?= lang('Auth.btn.submit') ?></button>
                        <a class="btn btn-secondary float-end" href="<?= base_url() ?>/users"><?= lang('Auth.btn.cancel') ?></a>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>