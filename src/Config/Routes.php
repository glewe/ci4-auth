<?php

//
// Lewe Auth
//
$routes->group('', ['namespace' => 'CI4\Auth\Controllers'], function ($routes) {

    // Sample route with role filter
    // $routes->match(['get', 'post'], 'roles', 'RoleController::index', ['filter' => 'role:Administrator']);

    $routes->get('/', 'AuthController::welcome');

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

    // Groups
    $routes->match(['get', 'post'], 'groups', 'GroupController::groups', ['as' => 'groups']);
    $routes->get('groups/create', 'GroupController::groupsCreate', ['as' => 'groupsCreate']);
    $routes->post('groups/create', 'GroupController::groupsCreateDo');
    $routes->get('groups/edit/(:num)', 'GroupController::groupsEdit/$1', ['as' => 'groupsEdit']);
    $routes->post('groups/edit/(:num)', 'GroupController::groupsEditDo/$1');

    // Permissions
    $routes->match(['get', 'post'], 'permissions', 'PermissionController::permissions', ['as' => 'permissions']);
    $routes->get('permissions/create', 'PermissionController::permissionsCreate', ['as' => 'permissionsCreate']);
    $routes->post('permissions/create', 'PermissionController::permissionsCreateDo');
    $routes->get('permissions/edit/(:num)', 'PermissionController::permissionsEdit/$1', ['as' => 'permissionsEdit']);
    $routes->post('permissions/edit/(:num)', 'PermissionController::permissionsEditDo/$1');

    // Roles
    $routes->match(['get', 'post'], 'roles', 'RoleController::roles', ['as' => 'roles']);
    $routes->get('roles/create', 'RoleController::rolesCreate', ['as' => 'rolesCreate']);
    $routes->post('roles/create', 'RoleController::rolesCreateDo');
    $routes->get('roles/edit/(:num)', 'RoleController::rolesEdit/$1', ['as' => 'rolesEdit']);
    $routes->post('roles/edit/(:num)', 'RoleController::rolesEditDo/$1');

    // Users
    $routes->match(['get', 'post'], 'users', 'UserController::users', ['as' => 'users']);
    $routes->get('users/create', 'UserController::usersCreate', ['as' => 'usersCreate']);
    $routes->post('users/create', 'UserController::usersCreateDo');
    $routes->get('users/edit/(:num)', 'UserController::usersEdit/$1', ['as' => 'usersEdit']);
    $routes->post('users/edit/(:num)', 'UserController::usersEditDo/$1');
});
