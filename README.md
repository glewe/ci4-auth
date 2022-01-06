# CI4-Auth
[![PHP](https://img.shields.io/badge/Language-PHP-8892BF.svg)](https://www.php.net/)
[![Bootstrap 5](https://img.shields.io/badge/Styles-Bootstrap%205-7952b3.svg)](https://www.getbootstrap.com/)
[![Font Awesome](https://img.shields.io/badge/Icons-Font%20Awesome%205-1e7cd6.svg)](https://www.fontawesome.com/)
[![Maintained](https://img.shields.io/badge/Maintained-yes-009900.svg)](https://GitHub.com/Naereen/StrapDown.js/graphs/commit-activity)

CI4-Auth is a user, group, role and permission management library for Codeigniter 4.

CI4-Auth is based on the great [Myth-Auth](https://github.com/lonnieezell/myth-auth) library for Codeigniter 4. Due credits go to its author [Lonnie Ezell](https://github.com/lonnieezell) and the
team for this awesome work.

I started customizing Myth-Auth to meet my specific requirements but after a while I noticed that my changes got
quite large. I decided to build CI4-Auth based on Myth-Auth, changing and adding features I needed for my projects.

## Requirements

- PHP 7.3+, 8.0+ (Attention: PHP 8.1 not supported yet by CI 4 as of 2022-01-01)
- CodeIgniter 4.0.4+

## Features

- Core Myth-Auth features
- Role objects are consistently called "role" in the code (e.g. tables, variables, classes)
- Added "Groups" as an addl. object, functioning just like roles
- Separated user controller functions from the Auth Controller
- Added views to manage users, groups, roles and permissions
- Added Bootstrap 5 and Font Awesome 5 support
- Added database seeders to create sample data
- Removed all languages but English and German (I don't speak anything else :-) )

## Installation

### Install Codeigniter

Install an appstarter project with Codigniter 4 as described [here](https://codeigniter.com/user_guide/installation/installing_composer.html).

Make sure your app and database is configured right and runs fine showing the Codigniter 4 welcome page.

### Download CI4-Auth

Download the CI4-Auth archive from this repo here.

### Copy CI4-Auth to your ThirdParty folder

*Note: CI4-Auth is not available as a Composer package yet. It works from your ThirdParty folder.*

Unzip the CI4-Auth archive and copy the 'lewe' directory to your **\app\ThirdParty** folder in your Codeigniter project.
You should see this tree section then:
```
project-root
- app
  - ThirdParty
    - lewe
      - ci4-auth
        - src
```

### Configuration

1. Add the Psr4 path in your **app/Config/Autoload.php** file as follows:
```php
public $psr4 = [
    APP_NAMESPACE  => APPPATH, // For custom app namespace
    'Config'       => APPPATH . 'Config',
    'CI4\Auth'     => APPPATH . 'ThirdParty/lewe/ci4-auth/src',
];
```

2. Edit **app/Config/Validation.php** and add the following value to the ruleSets array:
```php
public $ruleSets = [
    Rules::class,
    FormatRules::class,
    FileRules::class,
    CreditCardRules::class,
    \CI4\Auth\Authentication\Passwords\ValidationRules::class
];
```

3. The "Remember Me" functionality is turned off by default. It can be turned on by setting the
`$allowRemembering` variable to `true` in **lewe/ci4-auth/src/Config/Auth.php**.

4. Edit **app/Config/Email.php** and verify that `fromName` and `fromEmail` are set as they are used when sending
emails for password resets, etc.

### Routes

The CI4-Auth routes are defined in **lewe/ci4-auth/src/Config/Routes.php**. Copy the routes group from there to your
**app/Config/Routes.php** file, right after the 'Route Definitions' header comment.
```php
/*
* --------------------------------------------------------------------
* Route Definitions
* --------------------------------------------------------------------
*/
//
// CI4-Auth Routes
//
$routes->group('', ['namespace' => 'CI4\Auth\Src\Controllers'], function ($routes) {

    // Sample route with role filter
    // $routes->match(['get', 'post'], 'roles', 'RoleController::index', ['filter' => 'role:Administrator']);

    $routes->get('/', 'AuthController::welcome');
    
    ...

});
```

### Views

The views that come with CI4-Auth are based on [Bootstrap 5](http://getbootstrap.com/) and [Font Awesome 5](https://fontawesome.com/).

If you like to use your own view you can override them editing the `$views` array in
**lewe/ci4-auth/src/Config/Auth.php**:
```php
public $views = [

    // Welcome page
    'welcome'            => 'CI4\Auth\Views\welcome',

    // Auth
    'login'              => 'CI4\Auth\Views\auth\login',
    'register'           => 'CI4\Auth\Views\auth\register',
    'forgot'             => 'CI4\Auth\Views\auth\forgot',
    'reset'              => 'CI4\Auth\Views\auth\reset',

    // Groups
    'groups'             => 'CI4\Auth\Views\groups\list',
    'groupsCreate'       => 'CI4\Auth\Views\groups\create',
    'groupsEdit'         => 'CI4\Auth\Views\groups\edit',

    // Permissions
    'permissions'        => 'CI4\Auth\Views\permissions\list',
    'permissionsCreate'  => 'CI4\Auth\Views\permissions\create',
    'permissionsEdit'    => 'CI4\Auth\Views\permissions\edit',

    // Roles
    'roles'              => 'CI4\Auth\Views\roles\list',
    'rolesCreate'        => 'CI4\Auth\Views\roles\create',
    'rolesEdit'          => 'CI4\Auth\Views\roles\edit',

    // Users
    'users'              => 'CI4\Auth\Views\users\list',
    'usersCreate'        => 'CI4\Auth\Views\users\create',
    'usersEdit'          => 'CI4\Auth\Views\users\edit',

    // Emails
    'emailForgot'        => 'CI4\Auth\Views\emails\forgot',
    'emailActivation'    => 'CI4\Auth\Views\emails\activation',
];
```

### Database Migration

Assuming that your database is setup correctly but still empty you need to run the migrations now.

Copy the file **lewe/ci4-auth/src/Database/Migrations/2021-12-14-000000_create_auth_tables.php** to
**app/Database/Migrations**. Then run the command:

    > php spark migrate

### Database Seeding

Assuming that the migrations have been completed successfully, you can run the seeders now to create the CI4-Auth sample data.

Copy the files **lewe/ci4-auth/src/Database/Seeds/*.php** to **app/Database/Seeds**.
Then run the following command:

    > php spark db:seed CI4AuthSeeder

### Run Application

Start your browser and navigate to your public directory. Use the menu to check out the views that come with
CI4-Auth.

## Services

The Services did not change and are from the Myth-Auth core. See there for their documentation. 

## Helper Functions (Auth)

In addition to the helper functions that come with Myth-Auth, CI4-Auth provides these:

**dnd()**

* Function: Dump'n'Die. Returns a preformatted output of objects and variables.
* Parameters: Variable/Object, Switch to die after output or not
* Returns: Preformatted output

**in_groups()**

* Function: Ensures that the current user is in at least one of the passed in groups.
* Parameters: Group IDs or names (single item or array of items)
* Returns: `true` or `false`
*Note: This is not the same helper as in Myth-Auth since Myth-Auth is inconcistent in
using the terms 'group' and 'role'.*

**in_roles()**

* Function: Ensures that the current user is in at least one of the passed in roles.
* Parameters: Role IDs or names (single item or array of items).
* Returns: `true` or `false`
*Note: This is comparable to the in_groups() helper function in Myth-Auth.*

## Helper Functions (Bootstrap 5)

In order to create Bootstrap objects quicker and to avoid duplicating code in views, these helper functions are
provided:

**bs5_alert()**

* Function: Creates a Bootstrap 5 alert box.
* Parameters: Array with alert box details.
* Returns: HTML

**bs5_cardheader()**

* Function: Creates a Bootstrap card header.
* Parameters: Array with card header details.
* Returns: HTML

**bs5_formrow()**

* Function: Creates a two-column form field div (text, email, select, password).
* Parameters: Array with form field details.
* Returns: HTML

**bs5_modal()**

* Function: Creates a modal dialog.
* Parameters: Array with modal dialog details.
* Returns: HTML

**bs5_searchform()**

* Function: Creates a search form field.
* Parameters: Array with search form details.
* Returns: HTML

## Disclaimer

The CI4-Auth library is not perfect. It may very well contain bugs or things that can be done better. If you stumble upon such things, let me know.
Otherwise I hope the library will help you. Feel free to change anything to meet the requirements in your environment.

Enjoy,
George Lewe
