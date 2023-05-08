# CI4-Auth

## Installation

### Install Codeigniter

Install an appstarter project with CodIgniter 4 as described [here](https://codeigniter.com/user_guide/installation/installing_composer.html).

Make sure your app and database is configured right and runs fine showing the CodIgniter 4 welcome page.

Also, check the $baseUrl setting in **app/Config/App.php** points to your public directory to make sure that the navbar links work.

### Install RobThree TwoFactorAuth

The best way to install RobThree TwoFactorAuth is via composer:

    > composer require robthree/twofactorauth

### Download CI4-Auth

Download the CI4-Auth archive from this repo here.

### Copy CI4-Auth to your ThirdParty folder

_Note: CI4-Auth is not available as a Composer package yet. It works from your ThirdParty folder._

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

    ...

});
```
### Filters

Filters allow you to restrict access to routes based on conditions, e.g. a permission or role membership.

CI4-Auth comes with four filter classes: `Group`, `Login`, `Permission` and `Role`. They reside in `lewe/ci4-auth/src/Filters/`.
You can use those filters in your route specifications. The filter is checked before the route is executed. In the
following example, the user must hold the 'Administrator' role to open the route:

```php
$routes->match(['get', 'post'], 'roles', 'RoleController::index', ['filter' => 'role:Administrator']);
```
You can also add several filters like so:

```php
$routes->match(['get', 'post'], 'roles', 'RoleController::index', ['filter' => 'role:Administrator', 'filter' => 'group:Admins']);
```
You must register the aliases for those filters in your **app/Config/Filter.php** file:

```php
...
use CI4\Auth\Filters\GroupFilter;
use CI4\Auth\Filters\LoginFilter;
use CI4\Auth\Filters\PermissionFilter;
use CI4\Auth\Filters\RoleFilter;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     */
    public array $aliases = [
        ...
        'group'         => GroupFilter::class,
        'login'         => LoginFilter::class,
        'permission'    => PermissionFilter::class,
        'role'          => RoleFilter::class,
    ];
```
### Views

The views that come with CI4-Auth are based on [Bootstrap 5](http://getbootstrap.com/) and [Bootstrap Icons](https://icons.getbootstrap.com/).

If you like to use your own view you can override them editing the `$views` array in
**lewe/ci4-auth/src/Config/Auth.php**:

```php
public $views = [

    // Welcome page
    'welcome'            => 'CI4\Auth\Views\welcome',

    // Error page
    'error_auth'         => 'CI4\Auth\Views\error_auth',

    // Auth
    'login'              => 'CI4\Auth\Views\auth\login',
    'register'           => 'CI4\Auth\Views\auth\register',
    'forgot'             => 'CI4\Auth\Views\auth\forgot',
    'reset'              => 'CI4\Auth\Views\auth\reset',
    'setup2fa'           => 'CI4\Auth\Views\auth\setup2fa',
    'login2fa'           => 'CI4\Auth\Views\auth\login2fa',
    'whoami'             => 'CI4\Auth\Views\auth\whoami',

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

### Base Controller

In ***app/Controllers/BaseController.php** add 'auth', 'bs5' and 'session' to the $helpers array:

```php
    /**
     * An array of helpers to be loaded automatically upon class instantiation.
     * These helpers will be available to all other controllers that extend 
     * BaseController.
     *
     * @var array
     */
    protected $helpers = ['auth', 'bs5', 'session'];
```

### Passing custom config to Views

In case you have a custom configuration that you want to pass to your views (e.g. theme settings, language, etc.), the \_render() function of each CI4-Auth controller passes a variable called `$myConfig` to the view
if it exists. It is assumed that you set this variable in your BaseController.

### Database Migration

Assuming that your database is setup correctly but still empty you need to run the migrations now.

Copy the file **lewe/ci4-auth/src/Database/Migrations/..._create_auth_tables.php** to
**app/Database/Migrations**. Then run the command:

    > php spark migrate

### Database Seeding

Assuming that the migrations have been completed successfully, you can run the seeders now to create the CI4-Auth sample data.

Copy the files **lewe/ci4-auth/src/Database/Seeds/\*.php** to **app/Database/Seeds**.
Then run the following command:

    > php spark db:seed CI4AuthSeeder

All users created by the seed will have the password `Qwer!1234`.

### Run Application

Start your browser and navigate to your public directory. Use the menu to check out the views that come with
CI4-Auth.

### 2FA

You can configure two settings in **lewe/ci4-auth/src/Config/Auth.php**

The following setting determines whether users will be forced to setup 2FA.
```
public $require2FA = false;
```
The next setting specifies the title of the application as it appears in the authenticator App.
```
public $authenticatorTitle = 'CI4Auth';
```
The authenticator title will be suffixed the the user's email so that it will read:

_**CI4Auth: admin@mydomain.com**_

#### Removing Secret Keys

Users with 'Manage Users' permission can delete the secret key of user accounts from the user list page. 
This might become necessary when a user has a new device on which he must setup the authenticator App again.

### Languages

CI4-Auth provides language files for English, German and Spanish. You can change the language in your **app/Config/App.php** file:
```
public string $defaultLocale = 'en';
```
