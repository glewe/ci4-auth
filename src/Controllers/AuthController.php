<?php

namespace CI4\Auth\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Session\Session;

use CI4\Auth\Config\Auth as AuthConfig;
use CI4\Auth\Entities\User;
use CI4\Auth\Models\UserModel;
use RobThree\Auth\TwoFactorAuth;

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

    /**
     * @var TwoFactorAuth
     */
    protected $tfa;

    /**
     * The 2FA secret key will be encrypted before put into the database and
     * decrypted before used during the 2FA login. This is the cipher algo
     * for en- and decrypting.
     *
     * @var string
     */
    protected $cipher = "AES-256-CBC";

    /**
     * The 2FA secret key will be encrypted before put into the database and
     * decrypted before used during the 2FA login. This is the passphrase
     * for en- and decrypting.
     *
     * @var string
     */
    protected $passphrase;

    //-------------------------------------------------------------------------

    /**
     * Constructor.
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
        $this->tfa = new TwoFactorAuth($this->config->authenticatorTitle);
        $this->passphrase = hex2bin('8849523a8e0e1ff45f440da048428b2554d2660c80957fcedbeb9575c079d7eb');
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

        //
        // First things first - log the activation attempt.
        //
        $users->logActivationAttempt(
            $this->request->getGet('token'),
            $this->request->getIPAddress(),
            (string)$this->request->getUserAgent()
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

        //
        // Success!
        //
        return redirect()->route('login')->with('message', lang('Auth.activation.success'));
    }

    //-------------------------------------------------------------------------

    /**
     * Displays the CI4-Auth error page.
     */
    public function error()
    {
        return $this->_render($this->config->views['error_auth'], ['config' => $this->config]);
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

        //
        // Save the reset hash
        //
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
     * Displays the login form, or redirects the user to their destination/home
     * if they are already logged in.
     */
    public function login()
    {
        //
        // No need to show a login form if the user is already logged in.
        //
        if ($this->auth->check()) {
            $redirectURL = session('redirect_url') ?? site_url('/');
            unset($_SESSION['redirect_url']);
            return redirect()->to($redirectURL);
        }

        //
        // Set a return URL if none is specified
        //
        $_SESSION['redirect_url'] = session('redirect_url') ?? previous_url() ?? site_url('/');

        return $this->_render($this->config->views['login'], ['config' => $this->config]);
    }

    //-------------------------------------------------------------------------

    /**
     * Attempts to verify the user's credentials through a POST request.
     */
    public function loginDo()
    {
        $rules = [
            'login' => 'required',
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

        //
        // Determine credential type
        //
        $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        //
        // Try to log them in...
        //
        if (!$this->auth->attempt([$type => $login, 'password' => $password], $remember)) {
            return redirect()->back()->withInput()->with('error', $this->auth->error() ?? lang('Auth.login.bad_attempt'));
        }

        //
        // Is the user being forced to reset their password?
        //
        if ($this->auth->user()->force_pass_reset === true) {
            return redirect()->to(route_to('reset-password') . '?token=' . $this->auth->user()->reset_hash)->withCookies();
        }

        //
        // At this point we know that the user submitted the correct credentials.
        // Now we need to check whether a 2FA is required.
        //
        $users = model(UserModel::class);
        $user = $users->where('email', $this->auth->user()->email)->first();

        if ($this->auth->user()->hasSecret()) {
            //
            // User has setup 2FA. Save the user's email in a session variable
            // and redirect to 2FA login page.
            //
            session()->set('2fa_in_progress', $user->email);
            session()->set('ci4auth-remember', $remember);
            $redirectURL = site_url('/login2fa');
            return redirect()->to($redirectURL)->withCookies();
        } else {
            //
            // User has not setup 2FA.
            //
            if ($this->config->require2FA) {
                //
                // 2FA is required. Login the user and redirect to 2FA Setup.
                //
                session()->set('2fa_setup_required', $user->email);
                $redirectURL = site_url('/setup2fa');
                unset($_SESSION['redirect_url']);
                return redirect()->to($redirectURL)->withCookies();
            } else {
                //
                // 2FA is not setup and not required. Login the user.
                //
                $this->auth->login($user, $remember);
                $redirectURL = session('redirect_url') ?? site_url('/');
                unset($_SESSION['redirect_url']);
                return redirect()->to($redirectURL)->withCookies()->with('message', lang('Auth.login.success'));
            }
        }
    }

    //-------------------------------------------------------------------------

    /**
     * Displays the 2FA login page.
     */
    public function login2fa()
    {
        //
        // Redirect back if already logged in
        //
        if ($this->auth->check()) {
            $redirectURL = session('redirect_url') ?? site_url('/');
            unset($_SESSION['redirect_url']);
            return redirect()->to($redirectURL);
        }

        //
        // Redirect back if 2fa_in_progress cookie not set
        //
        if (!session('2fa_in_progress')) {
            $redirectURL = session('redirect_url') ?? site_url('/');
            unset($_SESSION['redirect_url']);
            return redirect()->to($redirectURL);
        }

        //
        // Get the remember setting from the login page
        //
        $remember = session('ci4auth_remember');

        $users = model(UserModel::class);
        $user = $users->where('email', session('2fa_in_progress'))->first();

        return $this->_render(
            $this->config->views['login2fa'],
            [
                'config' => $this->config,
                'user' => $user,
                'remember' => session('ci4auth-remember'),
            ]
        );
    }

    //-------------------------------------------------------------------------

    /**
     * Attempts to verify the user's 2FA PIN through a POST request.
     */
    public function login2faDo()
    {
        $rules = [
            'pin' => 'required|numeric',
        ];

        //
        // Validate input
        //
        $res = $this->validate($rules);
        if (!$res) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        //
        // Redirect back if 2fa_in_progress cookie not set
        //
        if (!session('2fa_in_progress')) {
            $redirectURL = session('redirect_url') ?? site_url('/');
            unset($_SESSION['redirect_url']);
            return redirect()->to($redirectURL)->withCookies()->with('errors', lang('Auth.2fa.login.no_2fa_in_progress'));
        }
        //
        // Check PIN
        //
        if ($this->request->getPost('pin')) {
            $users = model(UserModel::class);
            $user = $users->where('email', session('2fa_in_progress'))->first();
            //
            // Get the hashed secret, decrypt and verify the PIN with it.
            //
            $pin = $this->request->getPost('pin');
            $secret_hash = $user->getSecret();
            $secret = $this->decrypt($secret_hash, $this->passphrase);
            $verifyResult = $this->tfa->verifyCode($secret, $pin);
            if ($verifyResult) {
                //
                // Success. Log the user in.
                //
                $this->auth->login($user, session('ci4auth-remember'));
                $redirectURL = session('redirect_url') ?? site_url('/');
                unset($_SESSION['redirect_url']);
                unset($_SESSION['2fa_in_progress']);
                unset($_SESSION['ci4auth-remember']);
                return redirect()->to($redirectURL)->withCookies()->with('message', lang('Auth.login.success'));
            } else {
                //
                // No match. Reload page with the same secret.
                //
                $qrcode = $this->tfa->getQRCodeImageAsDataUri($user->email, $secret);
                session()->setFlashdata('error', lang('Auth.2fa.setup.mismatch'));
                return $this->_render(
                    $this->config->views['login2fa'],
                    [
                        'config' => $this->config,
                        'user' => $user,
                        'remember' => session('ci4auth-remember'),
                    ]
                );
            }
        } else {
            //
            // No PIN submitted.
            //
            return redirect()->back()->withInput()->with('errors', lang('Auth.2fa.authenticator_code_missing'));
        }
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
        //
        // Check if registration is allowed
        //
        if (!$this->config->allowRegistration) return redirect()->back()->withInput()->with('error', lang('Auth.register.disabled'));

        $users = model(UserModel::class);

        //
        // Validate basics first since some password rules rely on these fields
        //
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'firstname' => 'max_length[80]',
            'lastname' => 'max_length[80]',
            'displayname' => 'max_length[80]',
        ];

        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        //
        // Validate passwords since they can only be validated properly here
        //
        $rules = [
            'password' => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        //
        // Save the user
        //
        $allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
        $user = new User($this->request->getPost($allowedPostFields));

        $this->config->requireActivation === null ? $user->activate() : $user->generateActivateHash();

        //
        // Ensure default role gets assigned if set
        //
        if (!empty($this->config->defaultUserRole)) $users = $users->withRole($this->config->defaultUserRole);

        if (!$users->save($user)) return redirect()->back()->withInput()->with('errors', $users->errors());

        if ($this->config->requireActivation !== null) {
            $activator = service('activator');
            $sent = $activator->send($user);
            if (!$sent) return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.exception.unknown_error'));
            //
            // Success!
            //
            return redirect()->route('login')->with('message', lang('Auth.activation.success'));
        }

        //
        // Success!
        //
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
            'token' => $token,
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
            'token' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|strong_password',
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

        //
        // Reset token still valid?
        //
        if (!empty($user->reset_expires) && time() > $user->reset_expires->getTimestamp()) {
            return redirect()->back()->withInput()->with('error', lang('Auth.password.reset_token_expired'));
        }

        //
        // Success! Save the new password, and cleanup the reset hash.
        //
        $user->password = $this->request->getPost('password');
        $user->reset_hash = null;
        $user->reset_at = date('Y-m-d H:i:s');
        $user->reset_expires = null;
        $user->force_pass_reset = false;
        $users->save($user);

        return redirect()->route('login')->with('message', lang('Auth.forgot.reset_success'));
    }

    //-------------------------------------------------------------------------

    /**
     * Displays the 2FA setup page.
     *
     * @param string $secret - Optional, to show the same QR code on wrong verify
     */
    public function setup2fa($secret = null)
    {
        //
        // Redirect back if not logged in and no forced 2FA setup is in progress
        //
        if (!$this->auth->check() && !session('2fa_setup_required')) return redirect()->back();

        if (!$this->auth->check() && session('2fa_setup_required')) {
            //
            // The user got here after logging in fine with his credentials
            // but a 2FA setup is required. He must setup 2FA before he can be
            // logged in for good.
            //
            // At login, the user's email was saved in session('2fa_setup_required')
            // so we know who we are dealing with here.
            //
            $users = model(UserModel::class);
            $user = $users->where('email', session('2fa_setup_required'))->first();
            $has_secret = false;
        } else {
            //
            // The user called this page to setup his 2FA
            //
            $user = user();
            $has_secret = user()->hasSecret();
        }

        //
        // Init 2FA variables
        //
        if (!$secret) $secret = $this->tfa->createSecret();
        $qrcode = $this->tfa->getQRCodeImageAsDataUri($user->email, $secret);

        //
        // Render the page
        //
        return $this->_render(
            $this->config->views['setup2fa'],
            [
                'config' => $this->config,
                'qrcode' => $qrcode,
                'secret' => $secret,
                'user' => $user,
                'has_secret' => $has_secret,
            ]
        );
    }

    //-------------------------------------------------------------------------

    /**
     * Attempt to setup 2FA for a user.
     */
    public function setup2faDo()
    {
        $users = model(UserModel::class);
        $user = $users->where('email', $this->request->getPost('hidden_email'))->first();

        $rules = [
            'authenticator_code' => 'required|numeric',
        ];

        //
        // Validate input
        //
        $res = $this->validate($rules);
        if (!$res) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        //
        // Save the settings
        //
        if ($this->request->getPost('authenticator_code')) {
            $secret = $this->request->getPost('hidden_secret');
            $totp = $this->request->getPost('authenticator_code');
            $verifyResult = $this->tfa->verifyCode($secret, $totp);
            if ($verifyResult) {
                //
                // Success. Hash and save the secret to the user record.
                //
                $user->setSecret($this->encrypt($secret, $this->cipher));
                $users->save($user);
                //
                // In case this was a required setup, log in the user for
                // good and unset the session variable.
                //
                if (session('2fa_setup_required')) {
                    if (!session('ci4auth-remember') || session('ci4auth-remember') == NULL) {
                        $remember = false;
                    } else {
                        $remember = session('ci4auth-remember');
                    }
                    $this->auth->login($user, $remember);
                    unset($_SESSION['redirect_url']);
                    unset($_SESSION['2fa_in_progress']);
                    unset($_SESSION['2fa_setup_progress']);
                    unset($_SESSION['ci4auth-remember']);
                }
                return redirect()->route('/')->with('message', lang('Auth.2fa.setup.success'));
            } else {
                //
                // No match. Reload page with the same secret.
                //
                $qrcode = $this->tfa->getQRCodeImageAsDataUri($user->email, $secret);
                session()->setFlashdata('error', lang('Auth.2fa.setup.mismatch'));
                return $this->_render(
                    $this->config->views['setup2fa'],
                    [
                        'config' => $this->config,
                        'qrcode' => $qrcode,
                        'secret' => $secret,
                        'user' => $user,
                        'has_secret' => $user->hasSecret(),
                    ]
                );
            }
        } else {
            //
            // No code submitted.
            //
            return redirect()->back()->withInput()->with('errors', lang('Auth.2fa.setup.authenticator_code_missing'));
        }
    }

    //-------------------------------------------------------------------------

    /**
     * Displays the About page.
     */
    public function about()
    {
        return $this->_render($this->config->views['about'], ['config' => $this->config]);
    }

    //-------------------------------------------------------------------------

    /**
     * Displays the Welcome page.
     */
    public function welcome()
    {
        return $this->_render($this->config->views['welcome'], ['config' => $this->config]);
    }

    //-------------------------------------------------------------------------

    /**
     * Displays the Whoami page.
     */
    public function whoami()
    {
        return $this->_render($this->config->views['whoami'], ['config' => $this->config]);
    }

    //-------------------------------------------------------------------------

    /**
     * Encrypts (but does not authenticate) a string.
     *
     * @param string $plaintext - String to encrypt
     * @param boolean $encode - Return base64-encoded or not
     * @return string
     */
    protected function encrypt($plaintext, $encode = false)
    {
        $nonceSize = openssl_cipher_iv_length($this->cipher);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $plaintext,
            $this->cipher,
            $this->passphrase,
            OPENSSL_RAW_DATA,
            $nonce
        );

        //
        // Now let's pack the IV and the ciphertext together (concatenate).
        //
        if ($encode) {
            return base64_encode($nonce . $ciphertext);
        }

        return $nonce . $ciphertext;
    }

    //-------------------------------------------------------------------------

    /**
     * Decrypts (but does not verify) an encrypted string.
     *
     * @param string $ciphertext - Encrypted string
     * @param boolean $encoded - Is base64 encoded string submitted or not?
     * @return string
     */
    protected function decrypt($ciphertext, $encoded = false)
    {
        if ($encoded) {
            $message = base64_decode($ciphertext, true);
            if ($message === false) {
                throw new Exception('Encryption failure');
            }
        }

        $nonceSize = openssl_cipher_iv_length($this->cipher);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            $this->cipher,
            $this->passphrase,
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plaintext;
    }

    //-------------------------------------------------------------------------

    /**
     * Render View.
     *
     * @param string $view
     * @param array $data
     *
     * @return string
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
