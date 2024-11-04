<?php

namespace CI4\Auth\Authorization;

use CodeIgniter\Model;
use CodeIgniter\Events\Events;

use CI4\Auth\Authorization\GroupModel;
use CI4\Auth\Authorization\RoleModel;
use CI4\Auth\Authorization\PermissionModel;
use CI4\Auth\Entities\User;
use CI4\Auth\Models\UserModel;

class FlatAuthorization implements AuthorizeInterface {
  /**
   * @var array|string|null
   */
  public $error;

  /**
   * The group model to use. Usually the class noted below (or an extension
   * thereof) but can be any compatible CodeIgniter Model.
   *
   * @var GroupModel
   */
  protected $groupModel;

  /**
   * The role model to use. Usually the class noted below (or an extension
   * thereof) but can be any compatible CodeIgniter Model.
   *
   * @var RoleModel
   */
  protected $roleModel;

  /**
   * The permission model to use. Usually the class noted below (or an
   * extension thereof) but can be any compatible CodeIgniter Model.
   *
   * @var PermissionModel
   */
  protected $permissionModel;

  /**
   * The user model to use. Usually the class noted below (or an extension
   * thereof) but can be any compatible CodeIgniter Model.
   *
   * @var UserModel
   */
  protected $userModel = null;

  /**
   * --------------------------------------------------------------------------
   * Constructor.
   * --------------------------------------------------------------------------
   *
   * Stores the models.
   *
   * @param GroupModel      $groupModel
   * @param RoleModel       $roleModel
   * @param PermissionModel $permissionModel
   */
  public function __construct(Model $groupModel, Model $roleModel, Model $permissionModel) {
    $this->groupModel = $groupModel;
    $this->roleModel = $roleModel;
    $this->permissionModel = $permissionModel;
  }

  /**
   * --------------------------------------------------------------------------
   * Add Permission to Group.
   * --------------------------------------------------------------------------
   *
   * Adds a single permission to a single group.
   *
   * @param int|string $permission
   * @param int|string $group
   *
   * @return bool
   */
  public function addPermissionToGroup($permission, $group): bool {
    $permissionId = $this->getPermissionID($permission);
    $groupId = $this->getGroupID($group);

    if (!is_numeric($permissionId)) return false;

    if (!is_numeric($groupId)) return false;

    if (!$this->groupModel->addPermissionToRole($permissionId, $groupId)) {
      $this->error = $this->groupModel->errors();
      return false;
    }

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Add Permission to Role.
   * --------------------------------------------------------------------------
   *
   * Adds a single permission to a single role.
   *
   * @param int|string $permission
   * @param int|string $role
   *
   * @return bool
   */
  public function addPermissionToRole($permission, $role): bool {
    $permissionId = $this->getPermissionID($permission);
    $roleId = $this->getRoleID($role);

    if (!is_numeric($permissionId)) return false;

    if (!is_numeric($roleId)) return false;

    if (!$this->roleModel->addPermissionToRole($permissionId, $roleId)) {
      $this->error = $this->roleModel->errors();
      return false;
    }

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Add Permission to User.
   * --------------------------------------------------------------------------
   *
   * Assigns a single permission to a user, irregardless of permissions
   * assigned by roles. This is saved to the user's meta information.
   *
   * @param int|string $permission
   * @param int        $userId
   *
   * @return bool|null
   */
  public function addPermissionToUser($permission, int $userId): bool|null {
    $permissionId = $this->getPermissionID($permission);

    if (!is_numeric($permissionId)) return null;

    if (!Events::trigger('beforeAddPermissionToUser', $userId, $permissionId)) return false;

    $user = $this->userModel->find($userId);

    if (!$user) {
      $this->error = lang('Auth.user.not_found', [ $userId ]);
      return false;
    }

    /** @var User $user */
    $permissions = $user->getPermissions();

    if (!in_array($permissionId, $permissions)) {
      $res = $this->permissionModel->addPermissionToUser($permissionId, $user->id);
    }

    Events::trigger('didAddPermissionToUser', $userId, $permissionId);

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Add User to Group.
   * --------------------------------------------------------------------------
   *
   * Adds a user to group.
   *
   * @param int   $userid
   * @param mixed $group Either ID or name, fails on anything else
   *
   * @return bool|null
   */
  public function addUserToGroup(int $userid, $group): bool|null {
    if (empty($userid) || !is_numeric($userid)) return null;

    if (empty($group) || (!is_numeric($group) && !is_string($group))) return null;

    $groupId = $this->getGroupID($group);

    if (!Events::trigger('beforeAddUserToGroup', $userid, $groupId)) return false;

    if (!is_numeric($groupId)) return null;

    if (!$this->groupModel->addUserToGroup($userid, (int)$groupId)) {
      $this->error = $this->groupModel->errors();
      return false;
    }

    Events::trigger('didAddUserToGroup', $userid, $groupId);

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Add User to Role.
   * --------------------------------------------------------------------------
   *
   * Adds a user to role.
   *
   * @param int   $userid
   * @param mixed $role Either ID or name, fails on anything else
   *
   * @return bool|null
   */
  public function addUserToRole(int $userid, $role): bool|null {
    if (empty($userid) || !is_numeric($userid)) return null;

    if (empty($role) || (!is_numeric($role) && !is_string($role))) return null;

    $roleId = $this->getRoleID($role);

    if (!Events::trigger('beforeAddUserToRole', $userid, $roleId)) return false;

    if (!is_numeric($roleId)) return null;

    if (!$this->roleModel->addUserToRole($userid, (int)$roleId)) {
      $this->error = $this->roleModel->errors();
      return false;
    }

    Events::trigger('didAddUserToRole', $userid, $roleId);

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Create Permission.
   * --------------------------------------------------------------------------
   *
   * Creates a single permission.
   *
   * @param string $name
   * @param string $description
   *
   * @return mixed
   */
  public function createPermission(string $name, string $description = ''): mixed {
    $data = [
      'name' => $name,
      'description' => $description,
    ];

    $validation = service('validation', null, false);
    $validation->setRules([
      'name' => 'required|max_length[255]|is_unique[auth_permissions.name]',
      'description' => 'max_length[255]',
    ]);

    if (!$validation->run($data)) {
      $this->error = $validation->getErrors();
      return false;
    }

    $id = $this->permissionModel->skipValidation()->insert($data);

    if (is_numeric($id)) return (int)$id;

    $this->error = $this->permissionModel->errors();

    return false;
  }

  /**
   * --------------------------------------------------------------------------
   * Create Group.
   * --------------------------------------------------------------------------
   *
   * @param string $name
   * @param string $description
   *
   * @return mixed
   */
  public function createGroup(string $name, string $description = ''): mixed {
    $data = [
      'name' => $name,
      'description' => $description,
    ];

    $validation = service('validation', null, false);
    $validation->setRules(
      [
        'name' => 'required|max_length[255]|is_unique[auth_groups.name]',
        'description' => 'max_length[255]',
      ],
      [
        'name' => [
          'required' => 'You must enter a group name.',
          'max_length[255]' => 'The group name cannot be longer than 255 characters.',
          'is_unique[auth_groups.name]' => 'The group name already exists.',
        ],
        'description' => [
          'max_length[255]' => 'The description cannot be longer than 255 characters.',
        ],
      ]
    );

    if (!$validation->run($data)) {
      $this->error = $validation->getErrors();
      return false;
    }

    $id = $this->groupModel->skipValidation()->insert($data);

    if (is_numeric($id)) return (int)$id;

    $this->error = $this->groupModel->errors();

    return false;
  }

  //-------------------------------------------------------------------------
  /**
   * --------------------------------------------------------------------------
   * Create Role.
   * --------------------------------------------------------------------------
   *
   * @param string $name
   * @param string $description
   *
   * @return mixed
   */
  public function createRole(string $name, string $description = ''): mixed {
    $data = [
      'name' => $name,
      'description' => $description,
    ];

    $validation = service('validation', null, false);
    $validation->setRules(
      [
        'name' => 'required|max_length[255]|is_unique[auth_roles.name]',
        'description' => 'max_length[255]',
      ],
      [
        'name' => [
          'required' => 'You must enter a role name.',
          'max_length[255]' => 'The role name cannot be longer than 255 characters.',
          'is_unique[auth_roles.name]' => 'The role name already exists.',
        ],
        'description' => [
          'max_length[255]' => 'The description cannot be longer than 255 characters.',
        ],
      ]
    );

    if (!$validation->run($data)) {
      $this->error = $validation->getErrors();
      return false;
    }

    $id = $this->roleModel->skipValidation()->insert($data);

    if (is_numeric($id)) return (int)$id;

    $this->error = $this->roleModel->errors();

    return false;
  }

  /**
   * --------------------------------------------------------------------------
   * Delete Group.
   * --------------------------------------------------------------------------
   *
   * Deletes a single group.
   *
   * @param int $groupId
   *
   * @return bool
   */
  public function deleteGroup(int $groupId): bool {
    if (!$this->groupModel->delete($groupId)) {
      $this->error = $this->groupModel->errors();
      return false;
    }
    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Delete Permission.
   * --------------------------------------------------------------------------
   *
   * Deletes a single permission and removes that permission from all roles.
   *
   * @param int $permissionId
   *
   * @return bool
   */
  public function deletePermission(int $permissionId): bool {
    if (!$this->permissionModel->delete($permissionId)) {
      $this->error = $this->permissionModel->errors();
      return false;
    }
    // Remove the permission from all roles and groups
    $this->roleModel->removePermissionFromAllRoles($permissionId);
    $this->groupModel->removePermissionFromAllGroups($permissionId);
    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Delete Role.
   * --------------------------------------------------------------------------
   *
   * Deletes a single role.
   *
   * @param int $roleId
   *
   * @return bool
   */
  public function deleteRole(int $roleId): bool {
    if (!$this->roleModel->delete($roleId)) {
      $this->error = $this->roleModel->errors();
      return false;
    }
    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Does User Have Permission.
   * --------------------------------------------------------------------------
   *
   * Checks to see if a user has personal permission assigned to it (not via
   * a group or role).
   *
   * @param int|string $userId
   * @param int|string $permission
   *
   * @return bool|null
   */
  public function doesUserHavePermission($userId, $permission): bool|null {
    $permissionId = $this->getPermissionID($permission);

    if (!is_numeric($permissionId)) return false;

    if (empty($userId) || !is_numeric($userId)) return null;

    return $this->permissionModel->doesUserHavePermission($userId, $permissionId);
  }

  /**
   * --------------------------------------------------------------------------
   * Error.
   * --------------------------------------------------------------------------
   *
   * Returns any error(s) from the last call.
   *
   * @return array|string|null
   */
  public function error(): array|string|null {
    return $this->error;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Group ID.
   * --------------------------------------------------------------------------
   *
   * Given a group, will return the group ID. The group can be either
   * the ID or the name of the group.
   *
   * @param int|string $group
   *
   * @return int|false
   */
  protected function getGroupID($group): int|false {
    if (is_numeric($group)) return (int)$group;

    $g = $this->groupModel->where('name', $group)->first();

    if (!$g) {
      $this->error = lang('Auth.group.not_found', [ $group ]);
      return false;
    }

    return (int)$g->id;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Permission ID.
   * --------------------------------------------------------------------------
   *
   * Verifies that a permission (either ID or the name) exists and returns
   * the permission ID.
   *
   * @param int|string $permission
   *
   * @return int|false
   */
  protected function getPermissionID($permission): int|false {
    // If it's a number, we're done here.
    if (is_numeric($permission)) return (int)$permission;

    // Otherwise, pull it from the database.
    $p = $this->permissionModel->asObject()->where('name', $permission)->first();

    if (!$p) {
      $this->error = lang('Auth.permission.not_found', [ $permission ]);
      return false;
    }

    return (int)$p->id;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Role ID.
   * --------------------------------------------------------------------------
   *
   * Given a role, will return the role ID. The role can be either
   * the ID or the name of the role.
   *
   * @param int|string $role
   *
   * @return int|false
   */
  protected function getRoleID($role): int|false {
    if (is_numeric($role)) return (int)$role;
    $r = $this->roleModel->where('name', $role)->first();
    if (!$r) {
      $this->error = lang('Auth.role.not_found', [ $role ]);
      return false;
    }
    return (int)$r->id;
  }

  /**
   * --------------------------------------------------------------------------
   * Group.
   * --------------------------------------------------------------------------
   *
   * Grabs the details about a single group.
   *
   * @param int|string $group
   *
   * @return object|null
   */
  public function group($group): object|null {
    if (is_numeric($group)) return $this->groupModel->find((int)$group);
    return $this->groupModel->where('name', $group)->first();
  }

  /**
   * --------------------------------------------------------------------------
   * Groups.
   * --------------------------------------------------------------------------
   *
   * Grabs an array of all groups.
   *
   * @return array of objects
   */
  public function groups(): array {
    return $this->groupModel->orderBy('name', 'asc')->findAll();
  }

  /**
   * --------------------------------------------------------------------------
   * Group Permissions.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all permissions in the system for a group.
   * The group can be either the ID or the name of the group.
   *
   * @param int|string $group
   *
   * @return mixed
   */
  public function groupPermissions($group): mixed {
    if (is_numeric($group)) {
      return $this->groupModel->getPermissionsForGroup($group);
    } else {
      $g = $this->groupModel->where('name', $group)->first();
      return $this->groupModel->getPermissionsForGroup($g->id);
    }
  }

  /**
   * --------------------------------------------------------------------------
   * Has Permission.
   * --------------------------------------------------------------------------
   *
   * Checks whether a user has a given permission.
   *
   * @param int|string $permission Permission ID or name
   * @param int        $userId
   *
   * @return mixed
   */
  public function hasPermission($permission, int $userId): mixed {
    if (empty($permission) || (!is_string($permission) && !is_numeric($permission))) return null;

    if (empty($userId) || !is_numeric($userId)) return null;

    // Get the Permission ID
    $permissionId = $this->getPermissionID($permission);

    if (!is_numeric($permissionId)) return false;

    // First check the permission model. If that exists, then we're golden.
    if ($this->permissionModel->doesUserHavePermission($userId, (int)$permissionId)) return true;

    // Still here? Then we have one last check to make - any user private permissions.
    return $this->doesUserHavePermission($userId, (int)$permissionId);
  }

  /**
   * --------------------------------------------------------------------------
   * Has Permissions.
   * --------------------------------------------------------------------------
   *
   * Checks whether a user has any of the given permissions.
   *
   * Permissions can be either a string, with the name of the permission, an
   * INT with the ID of the permission, or an array of strings/ids of
   * permissions that the user must have ONE of.
   * (It's an OR check not an AND check)
   *
   * @param mixed $permissions Permission ID or name (or array of)
   * @param int   $userId
   *
   * @return bool|null
   */
  public function hasPermissions($permissions, int $userId): bool|null {
    if (empty($userId) || !is_numeric($userId)) return null;

    if (!is_array($permissions)) $permissions = [ $permissions ];
    if (empty($permissions)) return false;

    foreach ($permissions as $permission) {
      // Get the Permission ID
      $permissionId = $this->getPermissionID($permission);
      if (!is_numeric($permissionId)) return false;
      // First check the permission model. If that exists, then we're golden.
      if ($this->permissionModel->doesUserHavePermission($userId, (int)$permissionId)) return true;
      // Still here? Then we have one last check to make - any user private permissions.
      return $this->doesUserHavePermission($userId, (int)$permissionId);
    }
    return false;
  }

  /**
   * --------------------------------------------------------------------------
   * In Group.
   * --------------------------------------------------------------------------
   *
   * Checks whether a user is in a group.
   *
   * Groups can be either a string, with the name of the group, an INT with the
   * ID of the group, or an array of strings/ids that the user must belong to
   * ONE of. (It's an OR check not an AND check)
   *
   * @param mixed $groups
   * @param int   $userId
   *
   * @return bool
   */
  public function inGroup($groups, int $userId): bool {
    if ($userId === 0) return false;

    if (!is_array($groups)) $groups = [ $groups ];

    $userGroups = $this->groupModel->getGroupsForUser((int)$userId);

    if (empty($userGroups)) return false;

    foreach ($groups as $group) {
      if (is_numeric($group)) {
        $ids = array_column($userGroups, 'group_id');
        if (in_array($group, $ids)) return true;
      } else if (is_string($group)) {
        $names = array_column($userGroups, 'name');
        if (in_array($group, $names)) return true;
      }
    }

    return false;
  }

  /**
   * --------------------------------------------------------------------------
   * In Role.
   * --------------------------------------------------------------------------
   *
   * Checks whether a user is in a role.
   *
   * Roles can be either a string, with the name of the role, an INT
   * with the ID of the role, or an array of strings/ids that the
   * user must belong to ONE of. (It's an OR check not an AND check)
   *
   * @param mixed $roles
   * @param int   $userId
   *
   * @return bool
   */
  public function inRole($roles, int $userId): bool {
    if ($userId === 0) return false;

    if (!is_array($roles)) $roles = [ $roles ];

    $userRoles = $this->roleModel->getRolesForUser((int)$userId);

    if (empty($userRoles)) return false;

    foreach ($roles as $role) {
      if (is_numeric($role)) {
        $ids = array_column($userRoles, 'role_id');
        if (in_array($role, $ids)) return true;
      } else if (is_string($role)) {
        $names = array_column($userRoles, 'name');
        if (in_array($role, $names)) return true;
      }
    }

    return false;
  }

  /**
   * --------------------------------------------------------------------------
   * Permission.
   * --------------------------------------------------------------------------
   *
   * Returns the details about a single permission.
   *
   * @param int|string $permission
   *
   * @return object|null
   */
  public function permission($permission): object|null {
    if (is_numeric($permission)) return $this->permissionModel->find((int)$permission);
    return $this->permissionModel->like('name', $permission, 'none', null, true)->first();
  }

  /**
   * --------------------------------------------------------------------------
   * Permissions.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all permissions in the system.
   *
   * @return mixed
   */
  public function permissions(): mixed {
    return $this->permissionModel->orderBy('name', 'asc')->findAll();
  }

  /**
   * --------------------------------------------------------------------------
   * Remove Permission from Group.
   * --------------------------------------------------------------------------
   *
   * Removes a single permission from a group.
   *
   * @param int|string $permission
   * @param int|string $group
   *
   * @return mixed
   */
  public function removePermissionFromGroup($permission, $group): mixed {
    $permissionId = $this->getPermissionID($permission);
    $groupId = $this->getRoleID($group);

    if (!is_numeric($permissionId)) return false;

    if (!is_numeric($groupId)) return false;

    if (!$this->groupModel->removePermissionFromGroup($permissionId, $groupId)) {
      $this->error = $this->groupModel->errors();
      return false;
    }

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Remove Permission from Role.
   * --------------------------------------------------------------------------
   *
   * Removes a single permission from a role.
   *
   * @param int|string $permission
   * @param int|string $role
   *
   * @return mixed
   */
  public function removePermissionFromRole($permission, $role): mixed {
    $permissionId = $this->getPermissionID($permission);
    $roleId = $this->getRoleID($role);

    if (!is_numeric($permissionId)) return false;

    if (!is_numeric($roleId)) return false;

    if (!$this->roleModel->removePermissionFromRole($permissionId, $roleId)) {
      $this->error = $this->roleModel->errors();
      return false;
    }

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Remove all Permissions from User.
   * --------------------------------------------------------------------------
   *
   * Removes all individual permissions from a user.
   *
   * @param int $userId
   *
   * @return bool|null
   */
  public function removeAllPermissionsFromUser(int $userId): bool|null {
    if (empty($userId) || !is_numeric($userId)) return null;
    $userId = (int)$userId;
    if (!Events::trigger('beforeRemoveAllPermissionsFromUser', $userId)) return false;
    $this->permissionModel->removeAllPermissionsFromUser($userId);
    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Remove Permission from User.
   * --------------------------------------------------------------------------
   *
   * Removes a single permission from a user. Only applies to permissions
   * that have been assigned with addPermissionToUser, not to permissions
   * inherited based on roles they belong to.
   *
   * @param int|string $permission
   * @param int        $userId
   *
   * @return bool|mixed|null
   */
  public function removePermissionFromUser($permission, int $userId): bool|null {
    $permissionId = $this->getPermissionID($permission);
    if (!is_numeric($permissionId)) return false;
    if (empty($userId) || !is_numeric($userId)) return null;
    $userId = (int)$userId;
    if (!Events::trigger('beforeRemovePermissionFromUser', $userId, $permissionId)) return false;
    return $this->permissionModel->removePermissionFromUser($permissionId, $userId);
  }

  /**
   * --------------------------------------------------------------------------
   * Remove User from Group.
   * --------------------------------------------------------------------------
   *
   * Removes a single user from a group.
   *
   * @param int   $userId
   * @param mixed $group
   *
   * @return mixed
   */
  public function removeUserFromGroup(int $userId, $group): mixed {
    if (empty($userId) || !is_numeric($userId)) return null;

    if (empty($group) || (!is_numeric($group) && !is_string($group))) return null;

    $groupId = $this->getGroupID($group);

    if (!Events::trigger('beforeRemoveUserFromGroup', $userId, $groupId)) return false;

    if (!is_numeric($groupId)) return false;

    if (!$this->groupModel->removeUserFromGroup($userId, $groupId)) {
      $this->error = $this->groupModel->errors();
      return false;
    }

    Events::trigger('didRemoveUserFromGroup', $userId, $groupId);

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Remove User from Role.
   * --------------------------------------------------------------------------
   *
   * Removes a single user from a role.
   *
   * @param int   $userId
   * @param mixed $role
   *
   * @return mixed
   */
  public function removeUserFromRole(int $userId, $role): mixed {
    if (empty($userId) || !is_numeric($userId)) return null;

    if (empty($role) || (!is_numeric($role) && !is_string($role))) return null;

    $roleId = $this->getRoleID($role);

    if (!Events::trigger('beforeRemoveUserFromRole', $userId, $roleId)) return false;

    if (!is_numeric($roleId)) return false;

    if (!$this->roleModel->removeUserFromRole($userId, $roleId)) {
      $this->error = $this->roleModel->errors();
      return false;
    }

    Events::trigger('didRemoveUserFromRole', $userId, $roleId);

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Remove User from all Groups.
   * --------------------------------------------------------------------------
   *
   * Removes a user from all groups.
   *
   * @param int $userId
   *
   * @return bool|mixed|null
   */
  public function removeUserFromAllGroups(int $userId): bool|null {
    if (empty($userId) || !is_numeric($userId)) return null;

    $userId = (int)$userId;

    if (!Events::trigger('beforeRemoveUserFromAllGroups', $userId)) return false;

    return $this->groupModel->removeUserFromAllGroups($userId);
  }

  /**
   * --------------------------------------------------------------------------
   * * Remove User from all Roles.
   * * --------------------------------------------------------------------------
   * *
   * Removes a user from all roles.
   *
   * @param int $userId
   *
   * @return bool|mixed|null
   */
  public function removeUserFromAllRoles(int $userId): bool|null {
    if (empty($userId) || !is_numeric($userId)) return null;

    $userId = (int)$userId;

    if (!Events::trigger('beforeRemoveUserFromAllRoles', $userId)) return false;

    return $this->roleModel->removeUserFromAllRoles($userId);
  }

  /**
   * --------------------------------------------------------------------------
   * Role.
   * --------------------------------------------------------------------------
   *
   * Grabs the details about a single role.
   *
   * @param int|string $role
   *
   * @return object|null
   */
  public function role($role): object|null {
    if (is_numeric($role)) return $this->roleModel->find((int)$role);
    return $this->roleModel->where('name', $role)->first();
  }

  /**
   * --------------------------------------------------------------------------
   * Roles.
   * --------------------------------------------------------------------------
   *
   * Grabs an array of all roles.
   *
   * @return array of objects
   */
  public function roles(): array {
    return $this->roleModel->orderBy('name', 'asc')->findAll();
  }

  /**
   * --------------------------------------------------------------------------
   * Role Permissions.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all permissions in the system for a role
   * The role can be either the ID or the name of the role.
   *
   * @param int|string $role
   *
   * @return mixed
   */
  public function rolePermissions($role): mixed {
    if (is_numeric($role)) {
      return $this->roleModel->getPermissionsForRole($role);
    } else {
      $r = $this->roleModel->where('name', $role)->first();
      return $this->roleModel->getPermissionsForRole($r->id);
    }
  }

  /**
   * --------------------------------------------------------------------------
   * Set User Model.
   * --------------------------------------------------------------------------
   *
   * Allows the consuming application to pass in a reference to the
   * model that should be used.
   *
   * @param UserModel $model
   *
   * @return $this
   */
  public function setUserModel(Model $model): self {
    $this->userModel = $model;
    return $this;
  }

  /**
   * --------------------------------------------------------------------------
   * Update Group.
   * --------------------------------------------------------------------------
   *
   * Updates a single group's information.
   *
   * @param int    $id
   * @param string $name
   * @param string $description
   *
   * @return mixed
   */
  public function updateGroup(int $id, string $name, string $description = ''): mixed {
    $data = [
      'name' => $name,
    ];

    if (!empty($description)) $data['description'] = $description;

    if (!$this->groupModel->update($id, $data)) {
      $this->error = $this->groupModel->errors();
      return false;
    }

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Update Permission.
   * --------------------------------------------------------------------------
   *
   * Updates the details for a single permission.
   *
   * @param int    $id
   * @param string $name
   * @param string $description
   *
   * @return bool
   */
  public function updatePermission(int $id, string $name, string $description = ''): bool {
    $data = [
      'name' => $name,
    ];

    if (!empty($description)) $data['description'] = $description;

    if (!$this->permissionModel->update((int)$id, $data)) {
      $this->error = $this->permissionModel->errors();
      return false;
    }

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Update Role.
   * --------------------------------------------------------------------------
   *
   * Updates a single role's information.
   *
   * @param int    $id
   * @param string $name
   * @param string $description
   *
   * @return mixed
   */
  public function updateRole(int $id, string $name, string $description = ''): mixed {
    $data = [
      'name' => $name,
    ];

    if (!empty($description)) $data['description'] = $description;

    if (!$this->roleModel->update($id, $data)) {
      $this->error = $this->roleModel->errors();
      return false;
    }

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Users in Group.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all users in a group.
   * The group can be either the ID or the name of the group.
   *
   * @param int|string $group
   *
   * @return mixed
   */
  public function usersInGroup($group): mixed {
    if (is_numeric($group)) {
      return $this->groupModel->getUsersForGroup($group);
    } else {
      $g = $this->groupModel->where('name', $group)->first();
      return $this->groupModel->getUsersForGroup($g->id);
    }
  }

  /**
   * --------------------------------------------------------------------------
   * Users in Role.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all users in a role.
   * The role can be either the ID or the name of the role.
   *
   * @param int|string $role
   *
   * @return mixed
   */
  public function usersInRole($role): mixed {
    if (is_numeric($role)) {
      return $this->roleModel->getUsersForRole($role);
    } else {
      $g = $this->roleModel->where('name', $role)->first();
      return $this->roleModel->getUsersForRole($g->id);
    }
  }

  /**
   * --------------------------------------------------------------------------
   * User Groups.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all groups of a user.
   *
   * @param int $userId
   *
   * @return mixed
   */
  public function userGroups($userId): mixed {
    if (is_numeric($userId)) {
      return $this->groupModel->getGroupsForUser($userId);
    }
    return false;
  }

  /**
   * --------------------------------------------------------------------------
   * User Roles.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all roles of a user.
   *
   * @param int $userId
   *
   * @return mixed
   */
  public function userRoles($userId): mixed {
    if (is_numeric($userId)) {
      return $this->roleModel->getRolesForUser($userId);
    }
    return false;
  }
}
