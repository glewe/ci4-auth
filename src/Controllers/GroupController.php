<?php

namespace CI4\Auth\Controllers;

use CodeIgniter\Session\Session;

use CI4\Auth\Config\Auth as AuthConfig;
use CI4\Auth\Authorization\GroupModel;

use App\Controllers\BaseController;
use Config\Validation;

class GroupController extends BaseController {
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

  // -------------------------------------------------------------------------
  /**
   * Shows all user records.
   *
   * @return \CodeIgniter\HTTP\RedirectResponse | string
   */
  public function groups() {
    $groups = model(GroupModel::class);
    $allGroups = $groups->orderBy('name', 'asc')->findAll();

    $data = [
      'config' => $this->config,
      'groups' => $allGroups,
    ];

    foreach ($allGroups as $group) {
      $groupPermissions[ $group->id ][] = $groups->getPermissionsForGroup($group->id);
    }
    $data[ 'groupPermissions' ] = $groupPermissions;

    if ($this->request->withMethod('post')) {
      //
      // A form was submitted. Let's see what it was...
      //
      if (array_key_exists('btn_delete', $this->request->getPost())) {
        //
        // [Delete]
        //
        $recId = $this->request->getPost('hidden_id');
        if (!$group = $groups->where('id', $recId)->first()) {

          return redirect()->route('groups')->with('errors', lang('Auth.group.not_found', [ $recId ]));
        } else {

          if (!$groups->deleteGroup($recId)) {

            $this->session->set('errors', $groups->errors());
            return $this->_render($this->config->views[ 'groups' ], $data);
          }
          return redirect()->route('groups')->with('success', lang('Auth.group.delete_success', [ $group->name ]));
        }
      } else if (array_key_exists('btn_search', $this->request->getPost()) && array_key_exists('search', $this->request->getPost())) {
        //
        // [Search]
        //
        $search = $this->request->getPost('search');
        $where = '`name` LIKE "%' . $search . '%" OR `description` LIKE "%' . $search . '%"';;
        $data[ 'groups' ] = $groups->where($where)->orderBy('name', 'asc')->findAll();
        $data[ 'search' ] = $search;
      }
    }

    return $this->_render($this->config->views[ 'groups' ], $data);
  }

  //---------------------------------------------------------------------------
  /**
   * Displays the user create page.
   *
   * @return string
   */
  public function groupsCreate($id = null): string {
    return $this->_render($this->config->views[ 'groupsCreate' ], [ 'config' => $this->config ]);
  }

  //---------------------------------------------------------------------------
  /**
   * Attempt to create a new user.
   * To be be used by administrators. User will be activated automatically.
   *
   * @return \CodeIgniter\HTTP\RedirectResponse
   */
  public function groupsCreateDo() {
    $groups = model(GroupModel::class);
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
        'label' => lang('Auth.group.name'),
        'rules' => 'required|trim|max_length[255]|is_unique[auth_groups.name]',
        'errors' => [
          'is_unique' => lang('Auth.group.not_unique', [ $form[ 'name' ] ])
        ]
      ],
      'description' => [
        'label' => lang('Auth.group.description'),
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
      // Save the group
      // Return to Create screen on fail
      //
      $id = $this->auth->createGroup($this->request->getPost('name'), $this->request->getPost('description'));
      if (!$id) return redirect()->back()->withInput()->with('errors', $groups->errors());

      //
      // Success! Go back to user list
      //
      return redirect()->route('groups')->with('success', lang('Auth.group.create_success', [ $this->request->getPost('name') ]));
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Displays the user edit page.
   *
   * @param int $id Group ID
   */
  public function groupsEdit($id = null) {
    $groups = model(GroupModel::class);

    if (!$group = $groups->where('id', $id)->first()) return redirect()->to('groups');

    $permissions = $this->auth->permissions();
    $groupPermissions = $groups->getPermissionsForGroup($id);

    return $this->_render($this->config->views[ 'groupsEdit' ], [
      'config' => $this->config,
      'group' => $group,
      'permissions' => $permissions,
      'groupPermissions' => $groupPermissions,
    ]);
  }

  //---------------------------------------------------------------------------
  /**
   * Attempt to edit a group.
   *
   * @param int $id Group ID
   */
  public function groupsEditDo($id = null) {
    $groups = model(GroupModel::class);
    $form = array();

    //
    // Get the group to edit. If not found, return to groups list page.
    //
    if (!$group = $groups->where('id', $id)->first()) return redirect()->to('groups');

    //
    // Set basic validation rules for editing an existing group.
    //
    $validationRules = [
      'name' => [
        'label' => lang('Auth.group.name'),
        'rules' => 'required|trim|max_length[255]'
      ],
      'description' => [
        'label' => lang('Auth.group.description'),
        'rules' => 'permit_empty|trim|max_length[255]'
      ]
    ];

    //
    // Get form fields
    //
    $form[ 'name' ] = $this->request->getPost('name');
    $form[ 'description' ] = $this->request->getPost('description');

    //
    // If the group name changed, make sure the validator checks its uniqueness.
    //
    if ($form[ 'name' ] != $group->name) {
      $validationRules[ 'name' ] = [
        'label' => lang('Auth.group.name'),
        'rules' => 'required|trim|max_length[255]|is_unique[auth_groups.name]',
        'errors' => [
          'is_unique' => lang('Auth.group.not_unique', [ $form[ 'name' ] ])
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
      // Save the group name and description
      //
      $groups->update($id, $form);
      //
      // Now, save the permissions given to this group.
      // First, delete all permissions, then add each selected one.
      //
      $groups->removeAllPermissionsFromGroup((int)$id);
      if (array_key_exists('sel_permissions', $this->request->getPost())) {
        foreach ($this->request->getPost('sel_permissions') as $perm) {
          $groups->addPermissionToGroup($perm, $id);
        }
      }
      //
      // Success! Go back to groups list
      //
      return redirect()->back()->withInput()->with('success', lang('Auth.group.update_success', [ $group->name ]));
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
