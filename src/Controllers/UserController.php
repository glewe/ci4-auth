<?php

namespace CI4\Auth\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Session\Session;

use CI4\Auth\Config\Auth as AuthConfig;
use CI4\Auth\Entities\User;
use CI4\Auth\Models\UserModel;

use App\Controllers\BaseController;

class UserController extends BaseController
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

    //------------------------------------------------------------------------
    /**
     */
    public function __construct()
    {
        //
        // Most services in this controller require the session to be started
        //
        $this->session = service('session');

        $this->config = config('Auth');
        $this->authorize = service('authorization');
    }

    // ------------------------------------------------------------------------
    /**
     * Shows all user records.
     *
     * @return void
     */
    public function users()
    {
        $users = model(UserModel::class);

        $data = [
            'config' => $this->config,
            'users'  => $users->orderBy('username', 'asc')->findAll(),
        ];

        if ($this->request->getMethod() === 'post') {
            //
            // A form was submitted. Let's see what it was...
            //
            if (array_key_exists('btn_delete', $this->request->getPost())) {
                //
                // [Delete]
                //
                $recId = $this->request->getPost('hidden_id');
                if (!$user = $users->where('id', $recId)->first()) {
                    return redirect()->route('users')->with('errors', lang('Auth.user.not_found', [$recId]));
                } else {
                    if (!$users->deleteUser($recId)) {
                        $this->session->set('errors', $users->errors());
                        return $this->_render($this->config->views['users'], $data);
                    }
                    return redirect()->route('users')->with('success', lang('Auth.user.delete_success', [$user->username, $user->email]));
                }
            } else if (array_key_exists('btn_search', $this->request->getPost()) && array_key_exists('search', $this->request->getPost())) {
                //
                // [Search]
                //
                $search = $this->request->getPost('search');
                $where = '`username` LIKE "%' . $search . '%" OR `email` LIKE "%' . $search . '%" OR `firstname` LIKE "%' . $search . '%" OR `lastname` LIKE "%' . $search . '%"';
                $data['users'] = $users->where($where)->orderBy('username', 'asc')->findAll();
                $data['search'] = $search;
            }
        }

        return $this->_render($this->config->views['users'], $data);
    }

    //------------------------------------------------------------------------
    /**
     * Displays the user create page.
     */
    public function usersCreate($id = null)
    {
        return $this->_render($this->config->views['usersCreate'], ['config' => $this->config]);
    }

    //------------------------------------------------------------------------
    /**
     * Attempt to create a new user.
     * To be be used by administrators. User will be activated automatically.
     */
    public function usersCreateDo()
    {
        $users = model(UserModel::class);

        //
        // Validate basics first since some password rules rely on these fields
        //
        $rules = [
            'username'        => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'           => 'required|valid_email|is_unique[users.email]',
            'firstname'       => 'max_length[80]',
            'lastname'        => 'max_length[80]',
            'displayname'     => 'max_length[80]',
        ];

        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        //
        // Validate passwords since they can only be validated properly here
        //
        $rules = [
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        //
        // Save the user and activate
        //
        $allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
        $user = new User($this->request->getPost($allowedPostFields));
        $user->activate();

        //
        // Assign default role if set
        //
        if (!empty($this->config->defaultUserRole)) $users = $users->withRole($this->config->defaultUserRole);

        //
        // Generate password reset hash
        //
        if ($this->request->getPost('pass_resetmail')) {
            $user->forcePasswordReset();
            // $user->generateResetHash();
        }

        //
        // Save user record. Return to Create screen on fail.
        //
        if (!$users->save($user)) return redirect()->back()->withInput()->with('errors', $users->errors());

        //
        // Send password reset email to the created user
        //
        if ($this->request->getPost('pass_resetmail')) {
            $resetter = service('resetter');
            $sent = $resetter->send($user);
            if (!$sent) {
                return redirect()->back()->withInput()->with('error', $resetter->error() ?? lang('Auth.exception.unknown_error'));
            }
        }

        //
        // Success! Go back to user list
        //
        return redirect()->route('users')->with('success', lang('Auth.user.create_success', [$user->username, $user->email]));
    }

    //------------------------------------------------------------------------
    /**
     * Displays the user edit page.
     */
    public function usersEdit($id = null)
    {
        $users = model(UserModel::class);

        if (!$user = $users->where('id', $id)->first()) return redirect()->to('users');

        $groups = $this->authorize->groups();
        $permissions = $this->authorize->permissions();
        $roles = $this->authorize->roles();

        $userGroups = $this->authorize->userGroups($id);
        $userAllPermissions = $user->getPermissions();
        $userPersonalPermissions = $user->getPersonalPermissions();
        $userRoles = $this->authorize->userRoles($id);

        return $this->_render($this->config->views['usersEdit'], [
            'auth' => $this->authorize,
            'config' => $this->config,
            'user' => $user,
            'groups' => $groups,
            'permissions' => $permissions,
            'roles' => $roles,
            'userGroups' => $userGroups,
            'userAllPermissions' => $userAllPermissions,
            'userPersonalPermissions' => $userPersonalPermissions,
            'userRoles' => $userRoles,
        ]);
    }

    //------------------------------------------------------------------------
    /**
     * Attempt to create a new user.
     * To be be used by administrators. User will be activated automatically.
     */
    public function usersEditDo($id = null)
    {
        $users = model(UserModel::class);

        //
        // Get the user to edit. If not found, return to users list page.
        //
        if (!$user = $users->where('id', $id)->first()) return redirect()->to('users');

        //
        // Validate basics first since some password rules rely on these fields
        //
        $rules = [
            'email'           => 'required|valid_email|is_unique[users.email]',
            'username'        => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'firstname'       => 'max_length[80]',
            'lastname'        => 'max_length[80]',
            'displayname'     => 'max_length[80]',
        ];

        //
        // Don't validate uniqueness on email and username if the post will not change them.
        //
        $emailChange = true;
        if ($this->request->getPost('email') == $user->email) {

            $rules['email'] = 'required|valid_email';
            $emailChange = false;
        }

        $usernameChange = true;
        if ($this->request->getPost('username') == $user->username) {

            $rules['username'] = 'required|alpha_numeric_space|min_length[3]|max_length[30]';
            $usernameChange = false;
        }

        $lastnameChange = true;
        if ($this->request->getPost('lastname') == $user->lastname) $lastnameChange = false;

        $firstnameChange = true;
        if ($this->request->getPost('firstname') == $user->firstname) $firstnameChange = false;

        $displaynameChange = true;
        if ($this->request->getPost('displayname') == $user->displayname) $displaynameChange = false;

        //
        // Let's check whether there is any changed value at all.
        //
        if (
            !$emailChange &&
            !$usernameChange &&
            !$lastnameChange &&
            !$firstnameChange &&
            !$displaynameChange &&
            !$this->request->getPost('password')
        ) {
            //
            // No data of the user record have changed. Do nothing here.
            //
        } else {
            //
            // Validate input so far
            //
            if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

            $user->setAttribute('firstname', $this->request->getPost('firstname'));
            $user->setAttribute('lastname', $this->request->getPost('lastname'));
            $user->setAttribute('displayname', $this->request->getPost('displayname'));

            if ($emailChange)    $user->setAttribute('email', $this->request->getPost('email'));
            if ($usernameChange) $user->setAttribute('username', $this->request->getPost('username'));

            if ($this->request->getPost('password')) {
                //
                // Password change detected. Add it to the post fields and set it.
                //
                $rules = [
                    'password'     => 'required|strong_password',
                    'pass_confirm' => 'required|matches[password]',
                ];

                if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

                $allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
                $user->setPassword($this->request->getPost('password'));
            } else {
                //
                // Do not add Password to the post fields
                //
                $allowedPostFields = array_merge($this->config->validFields, $this->config->personalFields);
            }

            //
            // Update the user entity
            //
            if (!$users->update($id, $user)) return redirect()->back()->withInput()->with('errors', $users->errors());
        }

        //
        // Get the Active switch.
        //
        if ($this->request->getPost('swi_active')) $user->setAttribute('active', 1);
        else $user->setAttribute('active', 0);
        $users->update($id, $user);

        //
        // Handle the Groups tab
        //
        if (array_key_exists('sel_groups', $this->request->getPost())) {
            //
            // Delete all existing groups for this user first. Then add the posted ones.
            //
            $this->authorize->removeUserFromAllGroups((int)$id);

            foreach ($this->request->getPost('sel_groups') as $group) {

                $this->authorize->addUserToGroup($id, $group);
            }
        }

        //
        // Handle the Permissions tab
        //
        if (array_key_exists('sel_permissions', $this->request->getPost())) {
            //
            // Delete all existing permissions for this user first. Then add the posted ones.
            //
            $this->authorize->removeAllPermissionsFromUser((int)$id);
            foreach ($this->request->getPost('sel_permissions') as $perm) {

                $this->authorize->addPermissionToUser($perm, $id);
            }
        }

        //
        // Handle the Roles tab
        //
        if (array_key_exists('sel_roles', $this->request->getPost())) {
            //
            // Delete all existing groups for this user first. Then add the posted ones.
            //
            $this->authorize->removeUserFromAllRoles((int)$id);

            foreach ($this->request->getPost('sel_roles') as $role) {

                $this->authorize->addUserToRole($id, $role);
            }
        }

        //
        // Success! Go back to user list
        //
        return redirect()->back()->withInput()->with('success', lang('Auth.user.update_success', [$user->username, $user->email]));
    }

    //------------------------------------------------------------------------
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
