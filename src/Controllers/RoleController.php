<?php

namespace CI4\Auth\Controllers;

use CodeIgniter\Session\Session;

use CI4\Auth\Config\Auth as AuthConfig;
use CI4\Auth\Authorization\RoleModel;

use App\Controllers\BaseController;
use Config\Validation;

class RoleController extends BaseController {
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
   * @var Validation
   */
  protected $validation;

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   */
  public function __construct() {
    //
    // Most services in this controller require the session to be started
    //
    $this->session = service('session');
    $this->config = config('Auth');
    $this->auth = service('authorization');
    $this->validation = service('validation');
  }

  //-----------------------------------------------------------------------------
  /**
   * Shows all role records.
   *
   * @return \CodeIgniter\HTTP\RedirectResponse | string
   */
  public function roles() {
    $roles = model(RoleModel::class);
    $allRoles = $roles->orderBy('name', 'asc')->findAll();

    $data = [
      'config' => $this->config,
      'roles' => $allRoles,
    ];

    foreach ($allRoles as $role) {
      $rolePermissions[ $role->id ][] = $roles->getPermissionsForRole($role->id);
    }
    $data[ 'rolePermissions' ] = $rolePermissions;

    if ($this->request->withMethod('post')) {
      //
      // A form was submitted. Let's see what it was...
      //
      if (array_key_exists('btn_delete', $this->request->getPost())) {
        //
        // [Delete]
        //
        $recId = $this->request->getPost('hidden_id');
        if (!$role = $roles->where('id', $recId)->first()) {
          return redirect()->route('roles')->with('errors', lang('Auth.role.not_found', [ $recId ]));
        } else {
          if (!$roles->deleteRole($recId)) {
            $this->session->set('errors', $roles->errors());
            return $this->_render($this->config->views[ 'roles' ], $data);
          }
          return redirect()->route('roles')->with('success', lang('Auth.role.delete_success', [ $role->name ]));
        }
      } else if (array_key_exists('btn_search', $this->request->getPost()) && array_key_exists('search', $this->request->getPost())) {
        //
        // [Search]
        //
        $search = $this->request->getPost('search');
        $where = '`name` LIKE "%' . $search . '%" OR `description` LIKE "%' . $search . '%"';
        $data[ 'roles' ] = $roles->where($where)->orderBy('name', 'asc')->findAll();
        $data[ 'search' ] = $search;
      }
    }

    return $this->_render($this->config->views[ 'roles' ], $data);
  }

  //---------------------------------------------------------------------------
  /**
   * Displays the user create page.
   */
  public function rolesCreate($id = null) {
    return $this->_render($this->config->views[ 'rolesCreate' ], [ 'config' => $this->config ]);
  }

  //---------------------------------------------------------------------------
  /**
   * Attempt to create a new user.
   * To be be used by administrators. User will be activated automatically.
   */
  public function rolesCreateDo() {
    $roles = model(RoleModel::class);
    $form = array();

    //
    // Get form fields
    //
    $form[ 'name' ] = $this->request->getPost('name');
    $form[ 'description' ] = $this->request->getPost('description');

    //
    // Set validation rules for adding a new role
    //
    $validationRules = [
      'name' => [
        'label' => lang('Auth.role.name'),
        'rules' => 'required|trim|max_length[255]|is_unique[auth_roles.name]',
        'errors' => [
          'is_unique' => lang('Auth.role.not_unique', [ $form[ 'name' ] ])
        ]
      ],
      'description' => [
        'label' => lang('Auth.role.description'),
        'rules' => 'permit_empty|trim|max_length[255]'
      ]
    ];

    //
    // Validate input
    //
    $this->validation->setRules($validationRules);
    if ($this->validation->run($form) == FALSE) {
      //
      // Return validation error
      //
      return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
    } else {
      //
      // Save the role
      // Return to Create screen on fail
      //
      $id = $this->auth->createRole($this->request->getPost('name'), $this->request->getPost('description'));
      if (!$id) return redirect()->back()->withInput()->with('errors', $roles->errors());

      //
      // Success! Go back to role list
      //
      return redirect()->route('roles')->with('success', lang('Auth.role.create_success', [ $this->request->getPost('name') ]));
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Displays the user edit page.
   *
   * @param int $id Role ID
   */
  public function rolesEdit($id = null) {
    $roles = model(RoleModel::class);

    if (!$role = $roles->where('id', $id)->first()) return redirect()->to('roles');

    $permissions = $this->auth->permissions();
    $rolePermissions = $roles->getPermissionsForRole($id);

    return $this->_render($this->config->views[ 'rolesEdit' ], [
      'config' => $this->config,
      'role' => $role,
      'permissions' => $permissions,
      'rolePermissions' => $rolePermissions,
    ]);
  }

  //---------------------------------------------------------------------------
  /**
   * Attempt to create a new role.
   *
   * @param int $id Role ID
   */
  public function rolesEditDo($id = null) {
    $roles = model(RoleModel::class);
    $form = array();

    //
    // Get the role to edit. If not found, return to roles list page.
    //
    if (!$role = $roles->where('id', $id)->first()) return redirect()->to('roles');

    //
    // Set basic validation rules for editing an existing role.
    //
    $validationRules = [
      'name' => [
        'label' => lang('Auth.role.name'),
        'rules' => 'required|trim|max_length[255]'
      ],
      'description' => [
        'label' => lang('Auth.role.description'),
        'rules' => 'permit_empty|trim|max_length[255]'
      ]
    ];

    //
    // Get form fields
    //
    $form[ 'name' ] = $this->request->getPost('name');
    $form[ 'description' ] = $this->request->getPost('description');

    //
    // If the role name changed, make sure the validator checks its uniqueness.
    //
    if ($form[ 'name' ] != $role->name) {
      $validationRules[ 'name' ] = [
        'label' => lang('Auth.role.name'),
        'rules' => 'required|trim|max_length[255]|is_unique[auth_roles.name]',
        'errors' => [
          'is_unique' => lang('Auth.role.not_unique', [ $form[ 'name' ] ])
        ]
      ];
    }

    //
    // Validate input
    //
    $this->validation->setRules($validationRules);
    if ($this->validation->run($form) == FALSE) {
      //
      // Return validation error
      //
      return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
    } else {
      //
      // Save the role name and description
      //
      $res = $this->auth->updateRole($id, $form[ 'name' ], $form[ 'description' ]);
      if (!$res) return redirect()->back()->withInput()->with('errors', $roles->errors());

      //
      // Save the permissions given to this role.
      // First, delete all permissions, then add each selected one.
      //
      $roles->removeAllPermissionsFromRole((int)$id);
      if (array_key_exists('sel_permissions', $this->request->getPost())) {
        foreach ($this->request->getPost('sel_permissions') as $perm) {
          $roles->addPermissionToRole($perm, $id);
        }
      }

      //
      // Success! Go back to roles list
      //
      return redirect()->back()->withInput()->with('success', lang('Auth.role.update_success', [ $role->name ]));
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Render View.
   *
   * @param string $view
   * @param array $data
   *
   * @return string
   */
  protected function _render(string $view, array $data = []) {
    //
    // In case you have a custom configuration that you want to pass to
    // your views (e.g. theme settings), it is added here.
    //
    // It is assumed that have declared and set the variable $myConfig in
    // your BaseController.
    //
    if (isset($this->myConfig)) $data[ 'myConfig' ] = $this->myConfig;

    return view($view, $data);
  }
}
