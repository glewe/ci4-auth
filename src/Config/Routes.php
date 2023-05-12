<?php

//
// Lewe Auth
//
$routes->group('', ['namespace' => 'CI4\Auth\Controllers'], function ($routes) {

    // If you want to use group, login, permission or role filters in your route
    // definitions, you need to add the filter aliases to your Config/Filters.php file.
    // (see CI4-Auth readme).
    //
    // Sample routes with filters:
    // $routes->match(['get', 'post'], 'roles', 'RoleController::index', ['filter' => 'group:Disney']);
    // $routes->match(['get', 'post'], 'roles', 'RoleController::index', ['filter' => 'login']);
    // $routes->match(['get', 'post'], 'roles', 'RoleController::index', ['filter' => 'permission:View Roles']);
    // $routes->match(['get', 'post'], 'roles', 'RoleController::index', ['filter' => 'role:Administrator']);

    $routes->get('/', 'AuthController::welcome');
    $routes->get('/error_auth', 'AuthController::error');
    $routes->get('/about', 'AuthController::about');

    // Authentication
    $routes->get('login', 'AuthController::login', ['as' => 'login']);
    $routes->post('login', 'AuthController::loginDo');
    $routes->get('logout', 'AuthController::logout');
    $routes->get('register', 'AuthController::register', ['as' => 'register']);
    $routes->post('register', 'AuthController::registerDo');
    $routes->get('activate-account', 'AuthController::activateAccount', ['as' => 'activate-account']);
    $routes->get('resend-activate-account', 'AuthController::activateAccountResend', ['as' => 'resend-activate-account']);
    $routes->get('forgot', 'AuthController::forgotPassword', ['as' => 'forgot']);
    $routes->post('forgot', 'AuthController::forgotPasswordDo');
    $routes->get('reset-password', 'AuthController::resetPassword', ['as' => 'reset-password']);
    $routes->post('reset-password', 'AuthController::resetPasswordDo');
    $routes->get('setup2fa', 'AuthController::setup2fa', ['as' => 'setup2fa']);
    $routes->post('setup2fa', 'AuthController::setup2faDo');
    $routes->get('login2fa', 'AuthController::login2fa', ['as' => 'login2fa']);
    $routes->post('login2fa', 'AuthController::login2faDo');
    $routes->get('whoami', 'AuthController::whoami');

    // Groups
    $routes->match(['get', 'post'], 'groups', 'GroupController::groups', ['as' => 'groups', 'filter' => 'permission:groups.view']);
    $routes->get('groups/create', 'GroupController::groupsCreate', ['as' => 'groupsCreate', 'filter' => 'permission:groups.create']);
    $routes->post('groups/create', 'GroupController::groupsCreateDo', ['filter' => 'permission:groups.create']);
    $routes->get('groups/edit/(:num)', 'GroupController::groupsEdit/$1', ['as' => 'groupsEdit', 'filter' => 'permission:groups.edit']);
    $routes->post('groups/edit/(:num)', 'GroupController::groupsEditDo/$1', ['filter' => 'permission:groups.edit']);

    // Permissions
    $routes->match(['get', 'post'], 'permissions', 'PermissionController::permissions', ['as' => 'permissions', 'filter' => 'permission:permissions.view']);
    $routes->get('permissions/create', 'PermissionController::permissionsCreate', ['as' => 'permissionsCreate', 'filter' => 'permission:permissions.create']);
    $routes->post('permissions/create', 'PermissionController::permissionsCreateDo', ['filter' => 'permission:permissions.create']);
    $routes->get('permissions/edit/(:num)', 'PermissionController::permissionsEdit/$1', ['as' => 'permissionsEdit', 'filter' => 'permission:permissions.edit']);
    $routes->post('permissions/edit/(:num)', 'PermissionController::permissionsEditDo/$1', ['filter' => 'permission:permissions.edit']);

    // Roles
    $routes->match(['get', 'post'], 'roles', 'RoleController::roles', ['as' => 'roles', 'filter' => 'permission:roles.view']);
    $routes->get('roles/create', 'RoleController::rolesCreate', ['as' => 'rolesCreate', 'filter' => 'permission:roles.create']);
    $routes->post('roles/create', 'RoleController::rolesCreateDo', ['filter' => 'permission:roles.create']);
    $routes->get('roles/edit/(:num)', 'RoleController::rolesEdit/$1', ['as' => 'rolesEdit', 'filter' => 'permission:roles.edit']);
    $routes->post('roles/edit/(:num)', 'RoleController::rolesEditDo/$1', ['filter' => 'permission:roles.edit']);

    // Users
    $routes->match(['get', 'post'], 'users', 'UserController::users', ['as' => 'users', 'filter' => 'permission:users.view']);
    $routes->get('users/create', 'UserController::usersCreate', ['as' => 'usersCreate', 'filter' => 'permission:users.create']);
    $routes->post('users/create', 'UserController::usersCreateDo', ['filter' => 'permission:users.create']);
    $routes->get('users/edit/(:num)', 'UserController::usersEdit/$1', ['as' => 'usersEdit', 'filter' => 'permission:users.edit']);
    $routes->post('users/edit/(:num)', 'UserController::usersEditDo/$1', ['filter' => 'permission:users.edit']);
});
