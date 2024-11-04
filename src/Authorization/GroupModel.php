<?php

namespace CI4\Auth\Authorization;

use CodeIgniter\Model;

class GroupModel extends Model {
  protected $table = 'auth_groups';
  protected $primaryKey = 'id';
  protected $returnType = 'object';
  protected $allowedFields = [ 'name', 'description' ];
  protected $useTimestamps = false;
  protected $skipValidation = false;

  /**
   * --------------------------------------------------------------------------
   * Add Permission to Group.
   * --------------------------------------------------------------------------
   *
   * Add a single permission to a single group, by IDs.
   *
   * @param int $permissionId
   * @param int $groupId
   *
   * @return mixed
   */
  public function addPermissionToGroup(int $permissionId, int $groupId): bool {
    $data = [
      'group_id' => (int)$groupId,
      'permission_id' => (int)$permissionId,
    ];

    return $this->db->table('auth_groups_permissions')->insert($data);
  }

  /**
   * --------------------------------------------------------------------------
   * Add User to Group.
   * --------------------------------------------------------------------------
   *
   * Adds a single user to a single group.
   *
   * @param int $userId
   * @param int $groupId
   *
   * @return bool
   */
  public function addUserToGroup(int $userId, int $groupId): bool {
    cache()->delete("{$groupId}_users");
    cache()->delete("{$userId}_groups");
    cache()->delete("{$userId}_permissions");

    $data = [
      'user_id' => (int)$userId,
      'group_id' => (int)$groupId
    ];

    return (bool)$this->db->table('auth_groups_users')->insert($data);
  }

  /**
   * --------------------------------------------------------------------------
   * Delete Group.
   * --------------------------------------------------------------------------
   *
   * Deletes a group.
   *
   * @param int $id Group ID
   *
   * @return bool
   */
  public function deleteGroup(int $id): bool {
    if (!$this->delete($id)) {
      $this->error = $this->errors();
      return false;
    }

    return true;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Groups for User.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all groups that a user is a member of.
   *
   * @param int $userId
   *
   * @return array
   */
  public function getGroupsForUser(int $userId): array {
    if (null === $found = cache("{$userId}_groups")) {
      $found = $this->builder()
        ->select('auth_groups_users.*, auth_groups.name, auth_groups.description')
        ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups.id', 'left')
        ->where('user_id', $userId)
        ->get()->getResultArray();

      cache()->save("{$userId}_groups", $found, 300);
    }

    return $found;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Permissions for Group.
   * --------------------------------------------------------------------------
   *
   * Gets all permissions for a group in a way that can be easily used to
   * check against:
   *
   * [
   *   id => name,
   *   id => name
   * ]
   *
   * @param int $groupId
   *
   * @return array
   */
  public function getPermissionsForGroup(int $groupId): array {
    $permissionModel = model(PermissionModel::class);
    $fromGroup = $permissionModel
      ->select('auth_permissions.*')
      ->join('auth_groups_permissions', 'auth_groups_permissions.permission_id = auth_permissions.id', 'inner')
      ->where('group_id', $groupId)
      ->findAll();

    $found = [];
    foreach ($fromGroup as $permission) {
      $found[$permission->id] = $permission;
    }

    return $found;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Users for Group.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all users that are members of a group.
   *
   * @param int $groupId
   *
   * @return array
   */
  public function getUsersForGroup(int $groupId): array {
    if (null === $found = cache("{$groupId}_users")) {
      $found = $this->builder()
        ->select('auth_groups_users.*, users.*')
        ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups.id', 'left')
        ->join('users', 'auth_groups_users.user_id = users.id', 'left')
        ->where('auth_groups.id', $groupId)
        ->get()->getResultArray();

      cache()->save("{$groupId}_users", $found, 300);
    }

    return $found;
  }

  /**
   * --------------------------------------------------------------------------
   * Remove Permission from Group.
   * --------------------------------------------------------------------------
   *
   * Removes a single permission from a single group.
   *
   * @param int $permissionId
   * @param int $groupId
   *
   * @return mixed
   */
  public function removePermissionFromGroup(int $permissionId, int $groupId): bool {
    return $this->db->table('auth_groups_permissions')
      ->where([
        'permission_id' => $permissionId,
        'group_id' => $groupId
      ])->delete();
  }

  /**
   * --------------------------------------------------------------------------
   * Remove all Permissions from Group.
   * --------------------------------------------------------------------------
   *
   * Removes all permissions from a single group.
   *
   * @param int $groupId
   *
   * @return mixed
   */
  public function removeAllPermissionsFromGroup(int $groupId): bool {
    return $this->db->table('auth_groups_permissions')->where([ 'group_id' => $groupId ])->delete();
  }

  /**
   * --------------------------------------------------------------------------
   * Remove Permission from all Groups.
   * --------------------------------------------------------------------------
   *
   * Removes a single permission from all groups.
   *
   * @param int $permissionId
   *
   * @return mixed
   */
  public function removePermissionFromAllGroups(int $permissionId): bool {
    return $this->db->table('auth_groups_permissions')->where('permission_id', $permissionId)->delete();
  }

  /**
   * --------------------------------------------------------------------------
   * Remove User from Group.
   * --------------------------------------------------------------------------
   *
   * Removes a single user from a single group.
   *
   * @param int        $userId
   * @param int|string $groupId
   *
   * @return bool
   */
  public function removeUserFromGroup(int $userId, $groupId): bool {
    cache()->delete("{$groupId}_users");
    cache()->delete("{$userId}_groups");
    cache()->delete("{$userId}_permissions");

    return $this->db->table('auth_groups_users')->where([ 'user_id' => $userId, 'group_id' => (int)$groupId ])->delete();
  }

  /**
   * --------------------------------------------------------------------------
   * Remove User from all Groups.
   * --------------------------------------------------------------------------
   *
   * Removes a single user from all groups.
   *
   * @param int $userId
   *
   * @return bool
   */
  public function removeUserFromAllGroups(int $userId): bool {
    cache()->delete("{$userId}_groups");
    cache()->delete("{$userId}_permissions");

    return $this->db->table('auth_groups_users')->where('user_id', (int)$userId)->delete();
  }
}
