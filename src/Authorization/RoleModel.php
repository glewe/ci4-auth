<?php

namespace CI4\Auth\Authorization;

use CodeIgniter\Model;

class RoleModel extends Model {
  protected $table = 'auth_roles';
  protected $primaryKey = 'id';
  protected $returnType = 'object';
  protected $allowedFields = [ 'name', 'description' ];
  protected $useTimestamps = false;
  protected $skipValidation = false;

  /**
   * --------------------------------------------------------------------------
   * Add Permission to Role.
   * --------------------------------------------------------------------------
   *
   * Add a single permission to a single role, by IDs.
   *
   * @param int $permissionId
   * @param int $roleId
   *
   * @return bool
   */
  public function addPermissionToRole(int $permissionId, int $roleId): bool {
    $data = [
      'role_id' => (int)$roleId,
      'permission_id' => (int)$permissionId,
    ];

    return $this->db->table('auth_roles_permissions')->insert($data);
  }

  /**
   * --------------------------------------------------------------------------
   * Add User to Role.
   * --------------------------------------------------------------------------
   *
   * Adds a single user to a single role.
   *
   * @param int $userId
   * @param int $roleId
   *
   * @return bool
   */
  public function addUserToRole(int $userId, int $roleId): bool {
    cache()->delete("{$roleId}_users");
    cache()->delete("{$userId}_roles");
    cache()->delete("{$userId}_permissions");

    $data = [
      'user_id' => (int)$userId,
      'role_id' => (int)$roleId
    ];

    return (bool)$this->db->table('auth_roles_users')->insert($data);
  }

  /**
   * --------------------------------------------------------------------------
   * Delete Role.
   * --------------------------------------------------------------------------
   *
   * Deletes a role.
   *
   * @param int $id Role ID
   *
   * @return bool
   */
  public function deleteRole(int $id): bool {
    if (!$this->delete($id)) {
      $this->error = $this->errors();
      return false;
    }

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Permissions for Role.
   * --------------------------------------------------------------------------
   *
   * Gets all permissions for a role in a way that can be easily used to check
   * against:
   *
   * [
   *   id => name,
   *   id => name
   * ]
   *
   * @param int $roleId
   *
   * @return array
   */
  public function getPermissionsForRole(int $roleId): array {
    $permissionModel = model(PermissionModel::class);
    $fromRole = $permissionModel
      ->select('auth_permissions.*')
      ->join('auth_roles_permissions', 'auth_roles_permissions.permission_id = auth_permissions.id', 'inner')
      ->where('role_id', $roleId)
      ->findAll();

    $found = [];
    foreach ($fromRole as $permission) {
      $found[$permission->id] = $permission;
    }

    return $found;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Roles for User.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all roles that a user is a member of.
   *
   * @param int $userId
   *
   * @return array
   */
  public function getRolesForUser(int $userId): array {
    if (null === $found = cache("{$userId}_roles")) {
      $found = $this->builder()
        ->select('auth_roles_users.*, auth_roles.name, auth_roles.description')
        ->join('auth_roles_users', 'auth_roles_users.role_id = auth_roles.id', 'left')
        ->where('user_id', $userId)
        ->get()->getResultArray();

      cache()->save("{$userId}_roles", $found, 300);
    }

    return $found;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Users for Role.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all users that are member of a role.
   *
   * @param int $roleId
   *
   * @return array
   */
  public function getUsersForRole(int $roleId): array {
    if (null === $found = cache("{$roleId}_users")) {
      $found = $this->builder()
        ->select('auth_roles_users.*, users.*')
        ->join('auth_roles_users', 'auth_roles_users.role_id = auth_roles.id', 'left')
        ->join('users', 'auth_roles_users.user_id = users.id', 'left')
        ->where('auth_roles.id', $roleId)
        ->get()->getResultArray();

      cache()->save("{$roleId}_users", $found, 300);
    }

    return $found;
  }

  /**
   * --------------------------------------------------------------------------
   * Remove all Permissions from Role.
   * --------------------------------------------------------------------------
   *
   * Removes all permission from a single role.
   *
   * @param int $roleId
   *
   * @return mixed
   */
  public function removeAllPermissionsFromRole(int $roleId): bool {
    return $this->db->table('auth_roles_permissions')->where([ 'role_id' => $roleId ])->delete();
  }

  /**
   * --------------------------------------------------------------------------
   * Remove Permission from Role.
   * --------------------------------------------------------------------------
   *
   * Removes a single permission from a single role.
   *
   * @param int $permissionId
   * @param int $roleId
   *
   * @return mixed
   */
  public function removePermissionFromRole(int $permissionId, int $roleId): bool {
    return $this->db->table('auth_roles_permissions')
      ->where([
        'permission_id' => $permissionId,
        'role_id' => $roleId
      ])->delete();
  }

  /**
   * --------------------------------------------------------------------------
   * Remove Permission from all Roles.
   * --------------------------------------------------------------------------
   *
   * Removes a single permission from all roles.
   *
   * @param int $permissionId
   *
   * @return mixed
   */
  public function removePermissionFromAllRoles(int $permissionId): bool {
    return $this->db->table('auth_roles_permissions')->where('permission_id', $permissionId)->delete();
  }

  /**
   * --------------------------------------------------------------------------
   * Remove User from Role.
   * --------------------------------------------------------------------------
   *
   * Removes a single user from a single role.
   *
   * @param int        $userId
   * @param int|string $roleId
   *
   * @return bool
   */
  public function removeUserFromRole(int $userId, $roleId): bool {
    cache()->delete("{$roleId}_users");
    cache()->delete("{$userId}_roles");
    cache()->delete("{$userId}_permissions");

    return $this->db->table('auth_roles_users')->where([ 'user_id' => $userId, 'role_id' => (int)$roleId ])->delete();
  }

  /**
   * --------------------------------------------------------------------------
   * Remove User from all Roles.
   * --------------------------------------------------------------------------
   *
   * Removes a single user from all roles.
   *
   * @param int $userId
   *
   * @return bool
   */
  public function removeUserFromAllRoles(int $userId): bool {
    cache()->delete("{$userId}_roles");
    cache()->delete("{$userId}_permissions");

    return $this->db->table('auth_roles_users')->where('user_id', (int)$userId)->delete();
  }
}
