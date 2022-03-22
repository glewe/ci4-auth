<?php

namespace CI4\Auth\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Session\Session;

use CI4\Auth\Config\Auth as AuthConfig;
use CI4\Auth\Entities\User;
use CI4\Auth\Models\UserModel;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    protected $auth;

    /**
     * @var AuthConfig
     */
    protected $config;

    /**
     * @var Session
     */
    protected $session;

    //-------------------------------------------------------------------------
    /**
     */
    public function __construct()
    {
        //
        // Most services in this controller require the session to be started
        //
        $this->session = service('session');

        $this->config = config('Auth');
        $this->auth = service('authentication');
        $this->authorize = service('authorization');
    }

    //-------------------------------------------------------------------------
    /**
     * Activate account.
     *
     * @return mixed
     */
    public function activateAccount()
    {
        $users = model(UserModel::class);

        // First things first - log the activation attempt.
        $users->logActivationAttempt(
            $this->request->getGet('token'),
            $this->request->getIPAddress(),
            (string) $this->request->getUserAgent()
        );

        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 2, MINUTE) === false)
            return service('response')->setStatusCode(429)->setBody(lang('Auth.login.too_many_requests', [$throttler->getTokentime()]));

        $user = $users->where('activate_hash', $this->request->getGet('token'))->where('active', 0)->first();

        if (is_null($user))
            return redirect()->route('login')->with('error', lang('Auth.activation.no_user'));

        $user->activate();
        $users->save($user);

        return redirect()->route('login')->with('message', lang('Auth.register.success'));
    }

    //-------------------------------------------------------------------------
    /**
     * Resend activation account.
     *
     * @return mixed
     */
    public function activateAccountResend()
    {
        if ($this->config->requireActivation === null) {
            return redirect()->route('login');
        }

        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 2, MINUTE) === false) {
            return service('response')->setStatusCode(429)->setBody(lang('Auth.login.too_many_requests', [$throttler->getTokentime()]));
        }

        $login = urldecode($this->request->getGet('login'));
        $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $users = model(UserModel::class);

        $user = $users->where($type, $login)->where('active', 0)->first();

        if (is_null($user)) {
            return redirect()->route('login')->with('error', lang('Auth.activation.no_user'));
        }

        $activator = service('activator');
        $sent = $activator->send($user);

        if (!$sent) {
            return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.exception.unknown_error'));
        }

        // Success!
        return redirect()->route('login')->with('message', lang('Auth.activation.success'));
    }

    //-------------------------------------------------------------------------
    /**
     * Displays the forgot password form.
     */
    public function forgotPassword()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgot.disabled'));
        }

        return $this->_render($this->config->views['forgot'], ['config' => $this->config]);
    }

    //-------------------------------------------------------------------------
    /**
     * Attempts to find a user account with the given email address and sends
     * password reset instructions to them.
     */
    public function forgotPasswordDo()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgot.disabled'));
        }

        $users = model(UserModel::class);

        $user = $users->where('email', $this->request->getPost('email'))->first();

        if (is_null($user)) {
            return redirect()->back()->with('error', lang('Auth.forgot.no_user'));
        }

        // Save the reset hash /
        $user->generateResetHash();
        $users->save($user);

        $resetter = service('resetter');
        $sent = $resetter->send($user);

        if (!$sent) {
            return redirect()->back()->withInput()->with('error', $resetter->error() ?? lang('Auth.exception.unknown_error'));
        }

        return redirect()->route('reset-password')->with('message', lang('Auth.forgot.email_sent'));
    }

    //-------------------------------------------------------------------------
    /**
     * Displays the login form, or redirects
     * the user to their destination/home if
     * they are already logged in.
     */
    public function login()
    {
        // No need to show a login form if the user
        // is already logged in.
        if ($this->auth->check()) {
            $redirectURL = session('redirect_url') ?? site_url('/');
            unset($_SESSION['redirect_url']);
            return redirect()->to($redirectURL);
        }

        // Set a return URL if none is specified
        $_SESSION['redirect_url'] = session('redirect_url') ?? previous_url() ?? site_url('/');

        return $this->_render($this->config->views['login'], ['config' => $this->config]);
    }

    //-------------------------------------------------------------------------
    /**
     * Attempts to verify the user's credentials
     * through a POST request.
     */
    public function loginDo()
    {
        $rules = [
            'login'   => 'required',
            'password' => 'required',
        ];

        if ($this->config->validFields == ['email']) {
            $rules['login'] .= '|valid_email';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');
        $remember = (bool)$this->request->getPost('remember');

        // Determine credential type
        $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Try to log them in...
        if (!$this->auth->attempt([$type => $login, 'password' => $password], $remember)) {
            return redirect()->back()->withInput()->with('error', $this->auth->error() ?? lang('Auth.login.bad_attempt'));
        }

        // Is the user being forced to reset their password?
        if ($this->auth->user()->force_pass_reset === true) {
            return redirect()->to(route_to('reset-password') . '?token=' . $this->auth->user()->reset_hash)->withCookies();
        }

        $redirectURL = session('redirect_url') ?? site_url('/');
        unset($_SESSION['redirect_url']);

        return redirect()->to($redirectURL)->withCookies()->with('message', lang('Auth.login.success'));
    }

    //-------------------------------------------------------------------------
    /**
     * Log the user out.
     */
    public function logout()
    {
        if ($this->auth->check()) $this->auth->logout();
        return redirect()->to(site_url('/'));
    }

    //-------------------------------------------------------------------------
    /**
     * Displays the user registration page.
     */
    public function register()
    {
        //
        // Redirect back if already logged in
        //
        if ($this->auth->check()) return redirect()->back();

        //
        // Redirect back with error if registration is not allowed
        //
        if (!$this->config->allowRegistration) return redirect()->back()->withInput()->with('error', lang('Auth.register.disabled'));

        return $this->_render($this->config->views['register'], ['config' => $this->config]);
    }

    //-------------------------------------------------------------------------
    /**
     * Attempt to register a new user.
     */
    public function registerDo()
    {
        // Check if registration is allowed
        if (!$this->config->allowRegistration) return redirect()->back()->withInput()->with('error', lang('Auth.register.disabled'));

        $users = model(UserModel::class);

        // Validate basics first since some password rules rely on these fields
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'firstname'       => 'max_length[80]',
            'lastname'        => 'max_length[80]',
            'displayname'     => 'max_length[80]',
        ];

        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        // Validate passwords since they can only be validated properly here
        $rules = [
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        // Save the user
        $allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
        $user = new User($this->request->getPost($allowedPostFields));

        $this->config->requireActivation === null ? $user->activate() : $user->generateActivateHash();

        // Ensure default role gets assigned if set
        if (!empty($this->config->defaultUserRole)) $users = $users->withRole($this->config->defaultUserRole);

        if (!$users->save($user)) return redirect()->back()->withInput()->with('errors', $users->errors());

        if ($this->config->requireActivation !== null) {
            $activator = service('activator');
            $sent = $activator->send($user);
            if (!$sent) return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.exception.unknown_error'));
            // Success!
            return redirect()->route('login')->with('message', lang('Auth.activation.success'));
        }

        // Success!
        return redirect()->route('login')->with('message', lang('Auth.register.success'));
    }

    //-------------------------------------------------------------------------
    /**
     * Displays the Reset Password form.
     */
    public function resetPassword()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgot.disabled'));
        }

        $token = $this->request->getGet('token');

        return $this->_render($this->config->views['reset'], [
            'config' => $this->config,
            'token'  => $token,
        ]);
    }

    //-------------------------------------------------------------------------
    /**
     * Verifies the code with the email and saves the new password,
     * if they all pass validation.
     *
     * @return mixed
     */
    public function resetPasswordDo()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgot.disabled'));
        }

        $users = model(UserModel::class);

        // First things first - log the reset attempt.
        $users->logResetAttempt(
            $this->request->getPost('email'),
            $this->request->getPost('token'),
            $this->request->getIPAddress(),
            (string)$this->request->getUserAgent()
        );

        $rules = [
            'token'      => 'required',
            'email'      => 'required|valid_email',
            'password'    => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        //
        // For some reason, the validator code here does not work. It does not return.
        // I could not figure it out, therefore I added some manual validation below.
        //
        // $res = $this->validate($rules);
        // if (!$res) {
        //     return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        // }

        $valerror = false;
        $errmsg = array();

        if (!$this->request->getPost('token')) {
            $valerror = true;
            $errmsg[] = 'The token field is required.';
        }

        if (!$this->request->getPost('email')) {
            $valerror = true;
            $errmsg[] = 'The email field is required.';
        }

        if (!$this->request->getPost('password')) {
            $valerror = true;
            $errmsg[] = 'The password field is required.';
        }

        if (!$this->request->getPost('pass_confirm')) {
            $valerror = true;
            $errmsg[] = 'The password confirm field is required.';
        }

        if ($this->request->getPost('password') && $this->request->getPost('pass_confirm') && $this->request->getPost('password') != $this->request->getPost('pass_confirm')) {
            $valerror = true;
            $errmsg[] = 'The passwords must match.';
        }

        if ($valerror) {
            return redirect()->back()->withInput()->with('errors', $errmsg);
        }

        $user = $users
            ->where('email', $this->request->getPost('email'))
            ->where('reset_hash', $this->request->getPost('token'))
            ->first();

        if (is_null($user)) {
            return redirect()->back()->with('error', lang('Auth.forgot.no_user'));
        }

        // Reset token still valid?
        if (!empty($user->reset_expires) && time() > $user->reset_expires->getTimestamp()) {
            return redirect()->back()->withInput()->with('error', lang('Auth.password.reset_token_expired'));
        }

        // Success! Save the new password, and cleanup the reset hash.
        $user->password       = $this->request->getPost('password');
        $user->reset_hash       = null;
        $user->reset_at       = date('Y-m-d H:i:s');
        $user->reset_expires    = null;
        $user->force_pass_reset = false;
        $users->save($user);

        return redirect()->route('login')->with('message', lang('Auth.forgot.reset_success'));
    }

    //-------------------------------------------------------------------------
    /**
     * Displays the Lewe Auth welcome page.
     */
    public function welcome()
    {
        return $this->_render($this->config->views['welcome'], ['config' => $this->config]);
    }

    //-------------------------------------------------------------------------
    /**
     * Render View.
     *
     * @param string  $view
     * @param array   $data
     *
     * @return view
     */
    protected function _render(string $view, array $data = [])
    {
        //
        // In case you have a custom configuration that you want to pass to
        // your views (e.g. theme settings), it is added here.
        //
        // It is assumed that have declared and set the variable $myConfig in
        // your BaseController.
        //
        if (isset($this->myConfig)) $data['myConfig'] = $this->myConfig;

        return view($view, $data);
    }
}
