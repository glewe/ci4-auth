<?php

namespace CI4\Auth\Authorization;

use CodeIgniter\Model;

class PermissionModel extends Model {
  protected $table = 'auth_permissions';
  protected $primaryKey = 'id';
  protected $returnType = 'object';
  protected $allowedFields = [ 'name', 'description' ];
  protected $useTimestamps = false;

  /**
   * --------------------------------------------------------------------------
   * Add Permission to User.
   * --------------------------------------------------------------------------
   *
   * Adds a single permission to a single user.
   *
   * @param int $permissionId
   * @param int $userId
   *
   * @return bool
   */
  public function addPermissionToUser(int $permissionId, int $userId): bool {
    cache()->delete("{$userId}_permissions");

    return $this->db->table('auth_users_permissions')->insert([
      'user_id' => $userId,
      'permission_id' => $permissionId
    ]);
  }

  /**
   * --------------------------------------------------------------------------
   * Delete Permission.
   * --------------------------------------------------------------------------
   *
   * Deletes a permission.
   *
   * @param int $id Permission ID
   *
   * @return bool
   */
  public function deletePermission(int $id) {
    if (!$this->delete($id)) {
      $this->error = $this->errors();
      return false;
    }

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Does User have Permission.
   * --------------------------------------------------------------------------
   *
   * Checks if a user has a specific permission (personal, group, role).
   *
   * @param int $userId
   * @param int $permissionId
   *
   * @return bool
   */
  public function doesUserHavePermission(int $userId, int $permissionId): bool {
    //
    // Check user permissions and take advantage of caching
    //
    $userPerms = $this->getPermissionsForUser($userId);

    if (count($userPerms) && array_key_exists($permissionId, $userPerms)) return true;

    //
    // Check groups of the user for the permission
    //
    $count = $this->db->table('auth_groups_permissions')
      ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups_permissions.group_id', 'inner')
      ->where('auth_groups_permissions.permission_id', $permissionId)
      ->where('auth_groups_users.user_id', $userId)
      ->countAllResults();

    //
    // Check roles of the user for the permission
    //
    $count += $this->db->table('auth_roles_permissions')
      ->join('auth_roles_users', 'auth_roles_users.role_id = auth_roles_permissions.role_id', 'inner')
      ->where('auth_roles_permissions.permission_id', $permissionId)
      ->where('auth_roles_users.user_id', $userId)
      ->countAllResults();

    //
    // Return true for positive count, else 0
    //
    return $count > 0;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Permissions for User.
   * --------------------------------------------------------------------------
   *
   * Gets all personal, group and role permissions for a user in a way that can
   * be easily used to check against:
   *
   * [
   *   id => name,
   *   id => name
   * ]
   *
   * @param int $userId
   *
   * @return array
   */
  public function getPermissionsForUser(int $userId): array {
    if (null === $found = cache("{$userId}_permissions")) {

      //
      // Get personal permissions
      //
      $fromUser = $this->db->table('auth_users_permissions')
        ->select('id, auth_permissions.name')
        ->join('auth_permissions', 'auth_permissions.id = permission_id', 'inner')
        ->where('user_id', $userId)
        ->get()
        ->getResultObject();

      //
      // Get group permissions
      //
      $fromGroup = $this->db->table('auth_groups_users')
        ->select('auth_permissions.id, auth_permissions.name')
        ->join('auth_groups_permissions', 'auth_groups_permissions.group_id = auth_groups_users.group_id', 'inner')
        ->join('auth_permissions', 'auth_permissions.id = auth_groups_permissions.permission_id', 'inner')
        ->where('user_id', $userId)
        ->get()
        ->getResultObject();

      //
      // Get role permissions
      //
      $fromRole = $this->db->table('auth_roles_users')
        ->select('auth_permissions.id, auth_permissions.name')
        ->join('auth_roles_permissions', 'auth_roles_permissions.role_id = auth_roles_users.role_id', 'inner')
        ->join('auth_permissions', 'auth_permissions.id = auth_roles_permissions.permission_id', 'inner')
        ->where('user_id', $userId)
        ->get()
        ->getResultObject();

      $combined = array_merge($fromUser, $fromGroup, $fromRole);

      $found = [];
      foreach ($combined as $row) {
        $found[$row->id] = strtolower($row->name);
      }

      cache()->save("{$userId}_permissions", $found, 300);
    }

    return $found;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Personal Permissions for User.
   * --------------------------------------------------------------------------
   *
   * Gets all personal permissions for a user in a way that can be easily used
   * to check against:
   *
   * [
   *   id => name,
   *   id => name
   * ]
   *
   * @param int $userId
   *
   * @return array
   */
  public function getPersonalPermissionsForUser(int $userId): array {
    if (null === $found = cache("{$userId}_personal_permissions")) {
      //
      // Get personal permissions
      //
      $fromUser = $this->db->table('auth_users_permissions')
        ->select('id, auth_permissions.name')
        ->join('auth_permissions', 'auth_permissions.id = permission_id', 'inner')
        ->where('user_id', $userId)
        ->get()
        ->getResultObject();

      $found = [];
      foreach ($fromUser as $row) {
        $found[$row->id] = strtolower($row->name);
      }

      cache()->save("{$userId}_personal_permissions", $found, 300);
    }

    return $found;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Groups for Permission.
   * --------------------------------------------------------------------------
   *
   * Gets all groups that have a single permission assigned.
   *
   * @param int $permId Permission ID to check
   *
   * @return array
   */
  public function getGroupsForPermission(int $permId): array {
    if (null === $found = cache("{$permId}_permission_groups")) {
      //
      // Get personal permissions
      //
      $permGroups = $this->db->table('auth_groups_permissions')
        ->select('id, auth_groups.name')
        ->join('auth_groups', 'auth_groups.id = group_id', 'inner')
        ->where('permission_id', $permId)
        ->get()
        ->getResultObject();

      $found = [];
      foreach ($permGroups as $row) {
        $found[$row->id] = $row->name;
      }

      cache()->save("{$permId}_permissions_groups", $found, 300);
    }

    return $found;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Roles for Permission.
   * --------------------------------------------------------------------------
   *
   * Gets all groups that have a single permission assigned.
   *
   * @param int $permId Permission ID to check
   *
   * @return array
   */
  public function getRolesForPermission(int $permId): array {
    if (null === $found = cache("{$permId}_permission_roles")) {
      //
      // Get personal permissions
      //
      $permRoles = $this->db->table('auth_roles_permissions')
        ->select('id, auth_roles.name')
        ->join('auth_roles', 'auth_roles.id = role_id', 'inner')
        ->where('permission_id', $permId)
        ->get()
        ->getResultObject();

      $found = [];
      foreach ($permRoles as $row) {
        $found[$row->id] = $row->name;
      }

      cache()->save("{$permId}_permissions_roles", $found, 300);
    }

    return $found;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Users for Permission.
   * --------------------------------------------------------------------------
   *
   * Gets all users that hold a single personal permission.
   *
   * @param int $permId Permission ID to check
   *
   * @return array
   */
  public function getUsersForPermission(int $permId): array {
    if (null === $found = cache("{$permId}_permissions_users")) {
      //
      // Get personal permissions
      //
      $permUsers = $this->db->table('auth_users_permissions')
        ->select('id, users.username')
        ->join('users', 'users.id = user_id', 'inner')
        ->where('permission_id', $permId)
        ->get()
        ->getResultObject();

      $found = [];
      foreach ($permUsers as $row) {
        $found[$row->id] = $row->username;
      }

      cache()->save("{$permId}_permissions_users", $found, 300);
    }

    return $found;
  }

  /**
   * --------------------------------------------------------------------------
   * Remove Permission from User.
   * --------------------------------------------------------------------------
   *
   * Removes a permission from a user.
   *
   * @param int $permissionId
   * @param int $userId
   */
  public function removePermissionFromUser(int $permissionId, int $userId): void {
    $this->db->table('auth_users_permissions')->where([ 'user_id' => $userId, 'permission_id' => $permissionId ])->delete();
    cache()->delete("{$userId}_permissions");
  }

  /**
   * --------------------------------------------------------------------------
   * Remove all Permissions from User.
   * --------------------------------------------------------------------------
   *
   * Removes all permissions from a user.
   *
   * @param int $userId
   */
  public function removeAllPermissionsFromUser(int $userId): void {
    $this->db->table('auth_users_permissions')->where([ 'user_id' => $userId ])->delete();
    cache()->delete("{$userId}_permissions");
  }
}
