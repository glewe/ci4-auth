<?php

namespace CI4\Auth\Authorization;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'auth_roles';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $allowedFields = [
        'name', 'description'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'name' => 'required|max_length[255]|is_unique[auth_roles.name,name,{name}]',
        'description' => 'max_length[255]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    //-------------------------------------------------------------------------
    /**
     * Add a single permission to a single role, by IDs.
     *
     * @param int $permissionId
     * @param int $roleId
     *
     * @return mixed
     */
    public function addPermissionToRole(int $permissionId, int $roleId)
    {
        $data = [
            'role_id'         => (int)$roleId,
            'permission_id'   => (int)$permissionId,
        ];

        return $this->db->table('auth_roles_permissions')->insert($data);
    }

    //-------------------------------------------------------------------------
    /**
     * Adds a single user to a single role.
     *
     * @param int $userId
     * @param int $roleId
     *
     * @return bool
     */
    public function addUserToRole(int $userId, int $roleId)
    {
        cache()->delete("{$roleId}_users");
        cache()->delete("{$userId}_roles");
        cache()->delete("{$userId}_permissions");

        $data = [
            'user_id'  => (int) $userId,
            'role_id' => (int) $roleId
        ];

        return (bool) $this->db->table('auth_roles_users')->insert($data);
    }

    //-------------------------------------------------------------------------
    /**
     * Deletes a role.
     *
     * @param int  $id   Role ID
     *
     * @return bool
     */
    public function deleteRole(int $id)
    {
        if (!$this->delete($id)) {
            $this->error = $this->errors();
            return false;
        }

        return true;
    }

    //-------------------------------------------------------------------------
    /**
     * Gets all permissions for a role in a way that can be
     * easily used to check against:
     *
     * [
     *  id => name,
     *  id => name
     * ]
     *
     * @param int $roleId
     *
     * @return array
     */
    public function getPermissionsForRole(int $roleId): array
    {
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

    //-------------------------------------------------------------------------
    /**
     * Returns an array of all roles that a user is a member of.
     *
     * @param int $userId
     *
     * @return array
     */
    public function getRolesForUser(int $userId)
    {
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

    //-------------------------------------------------------------------------
    /**
     * Returns an array of all users that are members of a role.
     *
     * @param int $roleId
     *
     * @return array
     */
    public function getUsersForRole(int $roleId)
    {
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

    //--------------------------------------------------------------------
    /**
     * Removes all permission from a single role.
     *
     * @param int $roleId
     *
     * @return mixed
     */
    public function removeAllPermissionsFromRole(int $roleId)
    {
        return $this->db->table('auth_roles_permissions')->where(['role_id' => $roleId])->delete();
    }

    //--------------------------------------------------------------------
    /**
     * Removes a single permission from a single role.
     *
     * @param int $permissionId
     * @param int $roleId
     *
     * @return mixed
     */
    public function removePermissionFromRole(int $permissionId, int $roleId)
    {
        return $this->db->table('auth_roles_permissions')
            ->where([
                'permission_id'   => $permissionId,
                'role_id'         => $roleId
            ])->delete();
    }

    //--------------------------------------------------------------------
    /**
     * Removes a single permission from all roles.
     *
     * @param int $permissionId
     *
     * @return mixed
     */
    public function removePermissionFromAllRoles(int $permissionId)
    {
        return $this->db->table('auth_roles_permissions')->where('permission_id', $permissionId)->delete();
    }

    //-------------------------------------------------------------------------
    /**
     * Removes a single user from a single role.
     *
     * @param int $userId
     * @param int|string $roleId
     *
     * @return bool
     */
    public function removeUserFromRole(int $userId, $roleId)
    {
        cache()->delete("{$roleId}_users");
        cache()->delete("{$userId}_roles");
        cache()->delete("{$userId}_permissions");

        return $this->db->table('auth_roles_users')->where(['user_id'  => $userId, 'role_id' => (int) $roleId])->delete();
    }

    //-------------------------------------------------------------------------
    /**
     * Removes a single user from all roles.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function removeUserFromAllRoles(int $userId)
    {
        cache()->delete("{$userId}_roles");
        cache()->delete("{$userId}_permissions");

        return $this->db->table('auth_roles_users')->where('user_id', (int)$userId)->delete();
    }
}
