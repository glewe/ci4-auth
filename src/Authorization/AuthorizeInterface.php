<?php

namespace CI4\Auth\Authorization;

interface AuthorizeInterface {
  /**
   * Returns the latest error string.
   *
   * @return mixed
   */
  public function error();

  //=========================================================================
  // Actions
  //=========================================================================

  /**
   * Checks to see if a user is in a group.
   *
   * Groups can be either a string, with the name of the group, an INT with
   * the ID of the group, or an array of strings/ids that the user must belong
   * to ONE of. (It's an OR check not an AND check)
   *
   * @param mixed $groups
   * @param int $userId
   *
   * @return bool
   */
  public function inGroup($groups, int $userId);

  /**
   * Checks to see if a user is in a role.
   *
   * Roles can be either a string, with the name of the role, an INT with the
   * ID of the role, or an array of strings/ids that the user must belong to
   * ONE of. (It's an OR check not an AND check)
   *
   * @param mixed $roles
   * @param int $userId
   *
   * @return bool
   */
  public function inRole($roles, int $userId);

  /**
   * Checks a user's roles to see if they have the specified permission.
   *
   * @param int|string $permission
   * @param int $userId
   *
   * @return mixed
   */
  public function hasPermission($permission, int $userId);

  /**
   * Adds a user to a group.
   *
   * @param int $userid
   * @param int|string $group Either ID or name
   *
   * @return bool
   */
  public function addUserToGroup(int $userid, $group);

  /**
   * Adds a user to a role.
   *
   * @param int $userid
   * @param int|string $role Either ID or name
   *
   * @return bool
   */
  public function addUserToRole(int $userid, $role);

  /**
   * Removes a single user from a group.
   *
   * @param int $userId
   * @param int|string $group
   *
   * @return mixed
   */
  public function removeUserFromGroup(int $userId, $group);

  /**
   * Removes a single user from a role.
   *
   * @param int $userId
   * @param int|string $role
   *
   * @return mixed
   */
  public function removeUserFromRole(int $userId, $role);

  /**
   * Adds a single permission to a single group.
   *
   * @param int|string $permission
   * @param int|string $group
   *
   * @return mixed
   */
  public function addPermissionToGroup($permission, $group);

  /**
   * Adds a single permission to a single role.
   *
   * @param int|string $permission
   * @param int|string $role
   *
   * @return mixed
   */
  public function addPermissionToRole($permission, $role);

  /**
   * Removes a single permission from a group.
   *
   * @param int|string $permission
   * @param int|string $group
   *
   * @return mixed
   */
  public function removePermissionFromGroup($permission, $group);

  /**
   * Removes a single permission from a role.
   *
   * @param int|string $permission
   * @param int|string $role
   *
   * @return mixed
   */
  public function removePermissionFromRole($permission, $role);

  //=========================================================================
  // Groups
  //=========================================================================

  /**
   * Grabs the details about a single group.
   *
   * @param int|string $group
   *
   * @return object|null
   */
  public function group($group);

  /**
   * Grabs an array of all groups.
   *
   * @return array of objects
   */
  public function groups();

  /**
   * @param string $name
   * @param string $description
   *
   * @return mixed
   */
  public function createGroup(string $name, string $description = '');

  /**
   * Deletes a single group.
   *
   * @param int $groupId
   *
   * @return bool
   */
  public function deleteGroup(int $groupId);

  /**
   * Updates a single group's information.
   *
   * @param int $id
   * @param string $name
   * @param string $description
   *
   * @return mixed
   */
  public function updateGroup(int $id, string $name, string $description = '');

  //=========================================================================
  // Roles
  //=========================================================================

  /**
   * Grabs the details about a single role.
   *
   * @param int|string $role
   *
   * @return object|null
   */
  public function role($role);

  /**
   * Grabs an array of all roles.
   *
   * @return array of objects
   */
  public function roles();

  /**
   * @param string $name
   * @param string $description
   *
   * @return mixed
   */
  public function createRole(string $name, string $description = '');

  /**
   * Deletes a single role.
   *
   * @param int $roleId
   *
   * @return bool
   */
  public function deleteRole(int $roleId);

  /**
   * Updates a single role's information.
   *
   * @param int $id
   * @param string $name
   * @param string $description
   *
   * @return mixed
   */
  public function updateRole(int $id, string $name, string $description = '');

  //=========================================================================
  // Permissions
  //=========================================================================

  /**
   * Returns the details about a single permission.
   *
   * @param int|string $permission
   *
   * @return object|null
   */
  public function permission($permission);

  /**
   * Returns an array of all permissions in the system.
   *
   * @return mixed
   */
  public function permissions();

  /**
   * Creates a single permission.
   *
   * @param string $name
   * @param string $description
   *
   * @return mixed
   */
  public function createPermission(string $name, string $description = '');

  /**
   * Deletes a single permission and removes that permission from all roles.
   *
   * @param int $permissionId
   *
   * @return mixed
   */
  public function deletePermission(int $permissionId);

  /**
   * Updates the details for a single permission.
   *
   * @param int $id
   * @param string $name
   * @param string $description
   *
   * @return bool
   */
  public function updatePermission(int $id, string $name, string $description = '');
}
