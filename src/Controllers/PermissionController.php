<?php

namespace CI4\Auth\Controllers;

use CodeIgniter\Session\Session;

use CI4\Auth\Config\Auth as AuthConfig;
use CI4\Auth\Authorization\PermissionModel;

use App\Controllers\BaseController;
use Config\Validation;

class PermissionController extends BaseController {
  protected $auth;

  /**
   * @var AuthConfig
   */
  protected $authConfig;

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
    $this->authConfig = config('Auth');
    $this->auth = service('authorization');
    $this->validation = service('validation');
  }

  //---------------------------------------------------------------------------
  /**
   * Shows all permission records.
   *
   * @return \CodeIgniter\HTTP\RedirectResponse | string
   */
  public function permissions() {
    $permissions = model(PermissionModel::class);

    $data = [
      'config' => $this->authConfig,
      'permissions' => $permissions->orderBy('name', 'asc')->findAll(),
    ];

    if ($this->request->withMethod('post')) {
      //
      // A form was submitted. Let's see what it was...
      //
      if (array_key_exists('btn_delete', $this->request->getPost())) {
        //
        // [Delete]
        //
        $recId = $this->request->getPost('hidden_id');
        if (!$permission = $permissions->where('id', $recId)->first()) {
          return redirect()->route('permissions')->with('errors', lang('Auth.permission.not_found', [ $recId ]));
        } else {
          if (!$permissions->deletePermission($recId)) {
            $this->session->set('errors', $permissions->errors());
            return $this->_render($this->authConfig->views[ 'permissions' ], $data);
          }
          return redirect()->route('permissions')->with('success', lang('Auth.permission.delete_success', [ $permission->name ]));
        }
      } else if (array_key_exists('btn_search', $this->request->getPost()) && array_key_exists('search', $this->request->getPost())) {
        //
        // [Search]
        //
        $search = $this->request->getPost('search');
        $where = '`name` LIKE "%' . $search . '%" OR `description` LIKE "%' . $search . '%"';;
        $data[ 'permissions' ] = $permissions->where($where)->orderBy('name', 'asc')->findAll();
        $data[ 'search' ] = $search;
      }
    }

    //
    // Show the list view
    //
    return $this->_render($this->authConfig->views[ 'permissions' ], $data);
  }

  //---------------------------------------------------------------------------
  /**
   * Displays the user create page.
   */
  public function permissionsCreate($id = null) {
    return $this->_render($this->authConfig->views[ 'permissionsCreate' ], [ 'config' => $this->authConfig ]);
  }

  //---------------------------------------------------------------------------
  /**
   * Attempt to create a new user.
   * To be be used by administrators. User will be activated automatically.
   */
  public function permissionsCreateDo() {
    $permissions = model(PermissionModel::class);
    $form = array();

    //
    // Get form fields
    //
    $form[ 'name' ] = $this->request->getPost('name');
    $form[ 'description' ] = $this->request->getPost('description');

    //
    // Set validation rules for adding a new group
    //
    $validationRules = [
      'name' => [
        'label' => lang('Auth.permission.name'),
        'rules' => 'required|trim|max_length[255]|lower_alpha_dash_dot|is_unique[auth_permissions.name]',
        'errors' => [
          'is_unique' => lang('Auth.permission.not_unique', [ $form[ 'name' ] ])
        ]
      ],
      'description' => [
        'label' => lang('Auth.permission.description'),
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
      // Save the permission
      // Return to Create screen on fail
      //
      $id = $this->auth->createPermission(strtolower($this->request->getPost('name')), $this->request->getPost('description'));
      if (!$id) return redirect()->back()->withInput()->with('errors', $permissions->errors());

      //
      // Success! Go back to permission list
      //
      return redirect()->route('permissions')->with('success', lang('Auth.permission.create_success', [ $this->request->getPost('name') ]));
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Displays the user edit page.
   *
   * @param int $id Permission ID
   */
  public function permissionsEdit($id = null) {
    $permissions = model(PermissionModel::class);
    if (!$permission = $permissions->where('id', $id)->first()) return redirect()->to('permissions');

    $permGroups = $permissions->getGroupsForPermission($id);
    $permRoles = $permissions->getRolesForPermission($id);
    $permUsers = $permissions->getUsersForPermission($id);

    return $this->_render($this->authConfig->views[ 'permissionsEdit' ], [
      'config' => $this->authConfig,
      'permission' => $permission,
      'permGroups' => $permGroups,
      'permRoles' => $permRoles,
      'permUsers' => $permUsers,
    ]);
  }

  //---------------------------------------------------------------------------
  /**
   * Attempt to create a new permission.
   *
   * @param int $id Permission ID
   */
  public function permissionsEditDo($id = null) {
    $permissions = model(PermissionModel::class);
    $form = array();

    //
    // Get the permission to edit. If not found, return to permissions list page.
    //
    if (!$permission = $permissions->where('id', $id)->first()) return redirect()->to('permissions');

    //
    // Set basic validation rules for editing an existing permission.
    //
    $validationRules = [
      'name' => [
        'label' => lang('Auth.permission.name'),
        'rules' => 'required|trim|max_length[255]|lower_alpha_dash_dot'
      ],
      'description' => [
        'label' => lang('Auth.permission.description'),
        'rules' => 'permit_empty|trim|max_length[255]'
      ]
    ];

    //
    // Get form fields
    //
    $form[ 'name' ] = $this->request->getPost('name');
    $form[ 'description' ] = $this->request->getPost('description');

    //
    // If the permission name changed, make sure the validator checks its uniqueness.
    //
    if ($form[ 'name' ] != $permission->name) {
      $validationRules[ 'name' ] = [
        'label' => lang('Auth.permission.name'),
        'rules' => 'required|trim|max_length[255]|lower_alpha_dash_dot|is_unique[auth_permissions.name]',
        'errors' => [
          'is_unique' => lang('Auth.permission.not_unique', [ $form[ 'name' ] ])
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
      // Save the permission
      //
      $id = $this->auth->updatePermission($id, strtolower($form[ 'name' ]), $form[ 'description' ]);
      if (!$id) return redirect()->back()->withInput()->with('errors', $permissions->errors());

      //
      // Success! Go back to permissions list
      //
      return redirect()->back()->withInput()->with('success', lang('Auth.permission.update_success', [ $permission->name ]));
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Format permission name.
   *
   * @param string $name
   *
   * @return string
   */
  protected function _formatPermission(string $name) {
    return "";
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
