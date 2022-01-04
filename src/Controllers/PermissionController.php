<?php

namespace CI4\Auth\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Session\Session;

use CI4\Auth\Config\Auth as AuthConfig;
use CI4\Auth\Authorization\PermissionModel;

use App\Controllers\BaseController;

class PermissionController extends BaseController
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
        $this->auth = service('authorization');
    }

    // -------------------------------------------------------------------------
    /**
     * Shows all permission records.
     *
     * @return void
     */
    public function permissions()
    {
        $permissions = model(PermissionModel::class);

        $data = [
            'config' => $this->config,
            'permissions'  => $permissions->orderBy('name', 'asc')->findAll(),
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
                if (!$permission = $permissions->where('id', $recId)->first()) {
                    return redirect()->route('permissions')->with('errors', lang('Auth.permission.not_found', [$recId]));
                } else {
                    if (!$permissions->deletePermission($recId)) {
                        $this->session->set('errors', $permissions->errors());
                        return $this->_render($this->config->views['permissions'], $data);
                    }
                    return redirect()->route('permissions')->with('success', lang('Auth.permission.delete_success', [$permission->name]));
                }
            } else if (array_key_exists('btn_search', $this->request->getPost()) && array_key_exists('search', $this->request->getPost())) {
                //
                // [Search]
                //
                $search = $this->request->getPost('search');
                $where = '`name` LIKE "%' . $search . '%" OR `description` LIKE "%' . $search . '%"';;
                $data['permissions'] = $permissions->where($where)->orderBy('name', 'asc')->findAll();
                $data['search'] = $search;
            }
        }

        //
        // Show the list view
        //
        return $this->_render($this->config->views['permissions'], $data);
    }

    //------------------------------------------------------------------------
    /**
     * Displays the user create page.
     */
    public function permissionsCreate($id = null)
    {
        return $this->_render($this->config->views['permissionsCreate'], ['config' => $this->config]);
    }

    //------------------------------------------------------------------------
    /**
     * Attempt to create a new user.
     * To be be used by administrators. User will be activated automatically.
     */
    public function permissionsCreateDo()
    {
        $permissions = model(PermissionModel::class);

        //
        // Validate input
        //
        $rules = $permissions->validationRules;

        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        //
        // Save the permission
        // Return to Create screen on fail
        //
        $id = $this->auth->createPermission($this->request->getPost('name'), $this->request->getPost('description'));
        if (!$id) return redirect()->back()->withInput()->with('errors', $permissions->errors());

        //
        // Success! Go back to user list
        //
        return redirect()->route('permissions')->with('success', lang('Auth.permission.create_success', [$this->request->getPost('name')]));
    }

    //------------------------------------------------------------------------
    /**
     * Displays the user edit page.
     */
    public function permissionsEdit($id = null)
    {
        $permissions = model(PermissionModel::class);
        if (!$permission = $permissions->where('id', $id)->first()) return redirect()->to('permissions');

        $permGroups = $permissions->getGroupsForPermission($id);
        $permRoles = $permissions->getRolesForPermission($id);
        $permUsers = $permissions->getUsersForPermission($id);

        return $this->_render($this->config->views['permissionsEdit'], [
            'config' => $this->config,
            'permission' => $permission,
            'permGroups' => $permGroups,
            'permRoles' => $permRoles,
            'permUsers' => $permUsers,
        ]);
    }

    //------------------------------------------------------------------------
    /**
     * Attempt to create a new permission.
     */
    public function permissionsEditDo($id = null)
    {
        $permissions = model(PermissionModel::class);

        //
        // Get the permission to edit. If not found, return to permissions list page.
        //
        if (!$permission = $permissions->where('id', $id)->first()) return redirect()->to('permissions');

        //
        // Validate input
        //
        $rules = $permissions->validationRules;
        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());

        //
        // Save the permission
        //
        $id = $this->auth->updatePermission($id, $this->request->getPost('name'), $this->request->getPost('description'));
        if (!$id) return redirect()->back()->withInput()->with('errors', $permissions->errors());

        //
        // Success! Go back to permissions list
        //
        return redirect()->back()->withInput()->with('success', lang('Auth.permission.update_success', [$permission->name]));
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
        return view($view, $data);
    }
}
