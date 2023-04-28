<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

    <div class="container">

        <?= view('CI4\Auth\Views\_alert') ?>

        <div class="card">

            <?= bs5_cardheader(['icon' => 'fas fa-key', 'title' => lang('Auth.btn.editPermission'), 'help' => '#']) ?>

            <div class="card-body">

                <form action="<?= base_url() ?>/permissions/edit/<?= $permission->id ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="card">

                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="tabTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#tab-details" type="button" role="tab" aria-controls="details" aria-selected="true"><?= lang('Auth.permission.tab_details') ?></button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="usage-tab" data-bs-toggle="tab" data-bs-target="#tab-usage" type="button" role="tab" aria-controls="usage" aria-selected="false"><?= lang('Auth.permission.tab_usage') ?></button>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content" id="tabContent">

                                <!-- Tab: Details -->
                                <div class="tab-pane fade show active" id="tab-details" role="tabpanel" aria-labelledby="details-tab">
                                    <div class="card">
                                        <div class="card-body">

                                            <?php
                                            echo bs5_formrow([
                                                'type' => 'text',
                                                'mandatory' => true,
                                                'name' => 'name',
                                                'title' => lang('Auth.permission.name'),
                                                'desc' => lang('Auth.permission.name_desc'),
                                                'errors' => session('errors.name'),
                                                'value' => $permission->name
                                            ]);

                                            echo bs5_formrow([
                                                'type' => 'text',
                                                'mandatory' => false,
                                                'name' => 'description',
                                                'title' => lang('Auth.permission.description'),
                                                'desc' => lang('Auth.permission.description_desc'),
                                                'errors' => session('errors.description'),
                                                'value' => $permission->description
                                            ]);
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <!-- Tab: Usage -->
                                <div class="tab-pane fade" id="tab-usage" role="tabpanel" aria-labelledby="usage-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <?php
                                            $data = [
                                                'type' => 'select',
                                                'subtype' => 'multi',
                                                'name' => 'sel_perm_groups',
                                                'size' => '8',
                                                'mandatory' => false,
                                                'title' => lang('Auth.permission.perm_groups'),
                                                'desc' => lang('Auth.permission.perm_groups_desc') . '<br><span class="fst-italic text-secondary">' . lang('Auth.no_selection') . '</span>',
                                                'errors' => '',
                                            ];
                                            if (!empty($permGroups)) {
                                                foreach ($permGroups as $pg) {
                                                    $data['items'][] = [
                                                        'selected' => '',
                                                        'title' => $pg,
                                                        'value' => $pg,
                                                    ];
                                                }
                                            }
                                            echo bs5_formrow($data);

                                            $data = [
                                                'type' => 'select',
                                                'subtype' => 'multi',
                                                'name' => 'sel_perm_roles',
                                                'size' => '8',
                                                'mandatory' => false,
                                                'title' => lang('Auth.permission.perm_roles'),
                                                'desc' => lang('Auth.permission.perm_roles_desc') . '<br><span class="fst-italic text-secondary">' . lang('Auth.no_selection') . '</span>',
                                                'errors' => '',
                                            ];
                                            if (!empty($permRoles)) {
                                                foreach ($permRoles as $pr) {
                                                    $data['items'][] = [
                                                        'selected' => '',
                                                        'title' => $pr,
                                                        'value' => $pr,
                                                    ];
                                                }
                                            }
                                            echo bs5_formrow($data);

                                            $data = [
                                                'type' => 'select',
                                                'subtype' => 'multi',
                                                'name' => 'sel_perm_users',
                                                'size' => '8',
                                                'mandatory' => false,
                                                'title' => lang('Auth.permission.perm_users'),
                                                'desc' => lang('Auth.permission.perm_users_desc') . '<br><span class="fst-italic text-secondary">' . lang('Auth.no_selection') . '</span>',
                                                'errors' => '',
                                            ];
                                            if (!empty($permUsers)) {
                                                foreach ($permUsers as $pu) {
                                                    $data['items'][] = [
                                                        'selected' => '',
                                                        'title' => $pu,
                                                        'value' => $pu,
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
                            <a class="btn btn-secondary float-end" href="<?= base_url() ?>/permissions"><?= lang('Auth.btn.cancel') ?></a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

<?= $this->endSection() ?>