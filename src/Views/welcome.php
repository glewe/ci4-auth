<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container mb-4">

    <h3 class="mt-4">Welcome to CI4-Auth</h3>

    <p>CI4-Auth is based on the great <a href="https://github.com/lonnieezell/myth-auth" target="_blank">Myth-Auth</a> library for Codeigniter 4. Due credits go to its author Lonnie Ezell
        and the team for this awesome work.</p>

    <p>I tried to bend the code here and there to meet my specific requirements but after a while I noticed that my changes got quite large and decided to build <i>CI4-Auth</i> based on Myth-Auth,
        changing and adding features I needed for my project.</p>

    <h3 class="mt-4">Requirements</h3>

    <ul>
        <li>PHP 7.3+, 8.0+ (Attention: PHP 8.1 not supported yet by CI 4 as of 2022-01-01)</li>
        <li>Codeigniter 4.0.4+</li>
    </ul>

    <h3 class="mt-4">Features</h3>

    <ul>
        <li>Classic Myth-Auth features</li>
        <li>Role objects are consistently called "role" in the code (e.g. tables, variables, classes)</li>
        <li>Added "Groups" as an addl. object, functioning just like roles</li>
        <li>Separated user controller functions from the Auth Controller</li>
        <li>Added views to manage users, groups, roles and permissions</li>
        <li>Added Bootstrap 5 and Font Awesome 5 support</li>
        <li>Added database seeders to create sample data</li>
        <li>Removed all languages but English and German (I don't speak anything else :-) )</li>
    </ul>

    <h3 class="mt-4">Installation</h3>

    <h4 class="mt-4">1. Install Codeigniter</h4>
    <p>Install an appstarter project with Codigniter 4 as described here:<br>
        <a href="https://codeigniter.com/user_guide/installation/installing_composer.html" target="_blank">https://codeigniter.com/user_guide/installation/installing_composer.html</a>
    </p>
    <p>Make sure your app and database is configured right and runs fine showing the Codigniter 4 welcome page.</p>

    <h4 class="mt-4">2. Download CI4-Auth</h4>
    <p>Download the CI4-Auth archive from here:<br>
        <a href="#" target="_blank">CI4-Auth Archive</a>
    </p>

    <h4 class="mt-4">3. Copy CI4-Auth to your ThirdParty folder</h4>
    <p><i>Note: CI4-Auth is not available as a Composer package yet. It works from your ThirdParty folder as well.</i></p>
    <p>Unzip the CI4-Auth archive annd copy the 'lewe' directory to your \app\ThirdParty folder in your Codeigniter project. You should see this tree section then:</p>
    <pre class="card"><code class="card-body btn-dark">project-root
- app
  - ThirdParty
  <span style="color:#ffb000;">  - lewe
      - ci4-auth
        - src</span></code></pre>

    <h4 class="mt-4">4. Configuration</h4>
    <br>
    <ol>
        <li>Add the Psr4 path in your <strong>app/Config/Autoload.php</strong> file as follows:<br>
            <pre class="card"><code class="card-body btn-dark">public $psr4 = [
   APP_NAMESPACE  => APPPATH, // For custom app namespace
   'Config'       => APPPATH . 'Config',
   <span style="color:#ffb000;">'CI4\Auth'     => APPPATH . 'ThirdParty/lewe/ci4-auth/src',</span>
];</code></pre>
        </li>
        <br>
        <li>Edit <strong>app/Config/Validation.php</strong> and add the following value to the ruleSets array:<br>
            <pre class="card"><code class="card-body btn-dark">public $ruleSets = [
   Rules::class,
   FormatRules::class,
   FileRules::class,
   CreditCardRules::class,
   <span style="color:#ffb000;">\CI4\Auth\Authentication\Passwords\ValidationRules::class</span>
];</code></pre>
        </li>
        <br>
        <li>The "Remember Me" functionality is turned off by default though it can be turned on by setting the <var>$allowRemembering</var> variable to 'true' in <strong>lewe/ci4-auth/src/Config/Auth.php</strong>.</li>
        <br>
        <li>Edit <strong>app/Config/Email.php</strong> and verify that 'fromName' and 'fromEmail' are set as they are used when sending emails for password resets, etc.</li>
    </ol>

    <h4 class="mt-4">5. Routes</h4>
    <p>The CI4-Auth routes are defined in <strong>lewe/ci4-auth/src/Config/Routes.php</strong>. Copy the routes group from there to your <strong>app/Config/Routes.php</strong> file,
        right after the 'Route Definitions' header comment.</p>
    <pre class="card"><code class="card-body btn-dark">/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
<span style="color:#ffb000;">//
// CI4-Auth Routes
//
$routes->group('', ['namespace' => 'CI4\Auth\Src\Controllers'], function ($routes) {
   
   // Sample route with role filter
   // $routes->match(['get', 'post'], 'roles', 'RoleController::index', ['filter' => 'role:Administrator']);

   $routes->get('/', 'AuthController::welcome');
   
   ...

});</span></code></pre>

    <h4 class="mt-4">6. Database Migration</h4>
    <p>Assuming that your database is setup correctly but still empty you need to run the migrations now.</p>
    <p>Copy the file <strong>lewe/ci4-auth/src/Database/Migrations/2021-12-14-000000_create_auth_tables.php</strong> to <strong>app/Database/Migrations</strong>. Then run the command:</p>
    <p><code>php spark migrate</code></p>

    <h4 class="mt-4">7. Database Seeding</h4>
    <p>Assuming that the migrations have been completed successfully, you can run the seeders now to create the CI4-Auth sample data.</p>
    <p>Copy the files <strong>lewe/ci4-auth/src/Database/Seeds/*.php</strong> to <strong>app/Database/Seeds</strong>. Then run the following commands:</p>
    <p><code>php spark db:seed CI4AuthSeeder</code></p>

    <h3 class="mt-4">Run Application</h3>
    <p>Start your browser and navigate to your public directory. Use the menu to check out the views that come with CI4-Auth.</p>

    <h3 class="mt-4">Disclaimer</h3>
    <p>The CI4-Auth library is not perfect. It might contain bugs or things that can be done better. If you stumble upon such things, let me know.<br>
        Otherwise I hope the library will help you. Feel free to change anything to meet the requirements in your environment.</p>
    <p>Enjoy,<br>
        George Lewe</p>

</div>

<?= $this->endSection() ?>