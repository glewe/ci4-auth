<?php

namespace CI4\Auth\Models;

use CodeIgniter\Model;
use CI4\Auth\Authorization\GroupModel;
use CI4\Auth\Authorization\RoleModel;
use CI4\Auth\Entities\User;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = User::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'email',
        'username',
        'lastname',
        'firstname',
        'displayname',
        'password_hash',
        'secret_hash',
        'reset_hash',
        'reset_at',
        'reset_expires',
        'activate_hash',
        'status',
        'status_message',
        'active',
        'force_pass_reset',
        'permissions',
        'deleted_at',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'email' => ['required', 'valid_email', 'is_unique[users.email,id,{id}]'],
        'username' => ['required', 'alpha_numeric_punct', 'min_length[3]', 'max_length[30]', 'is_unique[users.username,id,{id}]'],
        'firstname' => 'max_length[120]',
        'lastname' => 'max_length[120]',
        'displayname' => 'max_length[120]',
        'password_hash' => 'required',
    ];

    protected $validationMessages = [
        'email' => [
            'required' => 'You must enter an email address.',
            'valid_email' => 'Please enter a valid email address.',
            'is_unique[users.email,email,{$data["name"]}]' => 'This email address is already taken.',
        ],
        'username' => [
            'required' => 'You must enter a username.',
            'max_length[80]' => 'The username cannot be longer than 80 characters.',
            'is_unique[users.username,username,{$data["username"]}]' => 'This username is already taken.',
        ],
        'firstname' => [
            'max_length[120]' => 'The first name cannot be longer than 120 characters.',
        ],
        'lastname' => [
            'max_length[120]' => 'The last name cannot be longer than 120 characters.',
        ],
        'displayname' => [
            'max_length[120]' => 'The display name cannot be longer than 120 characters.',
        ],
    ];

    protected $skipValidation = false;

    protected $afterInsert = ['addToRole'];

    /**
     * The id of a group to assign.
     * Set internally by withRole.
     *
     * @var int|null
     */
    protected $assignGroup;

    /**
     * The id of a role to assign.
     * Set internally by withRole.
     *
     * @var int|null
     */
    protected $assignRole;

    //-------------------------------------------------------------------------

    /**
     * If a default role is assigned in Config\Auth, will add this user to that
     * role. Will do nothing if the role cannot be found.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    protected function addToGroup($data)
    {
        if (is_numeric($this->assignGroup)) {
            $groupModel = model(GroupModel::class);
            $groupModel->addUserToGroup($data['id'], $this->assignGroup);
        }

        return $data;
    }

    //-------------------------------------------------------------------------

    /**
     * If a default role is assigned in Config\Auth, will
     * add this user to that role. Will do nothing
     * if the role cannot be found.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    protected function addToRole($data)
    {
        if (is_numeric($this->assignRole)) {
            $roleModel = model(RoleModel::class);
            $roleModel->addUserToRole($data['id'], $this->assignRole);
        }

        return $data;
    }

    //-------------------------------------------------------------------------

    /**
     * Clears the group to assign to newly created users.
     *
     * @return $this
     */
    public function clearGroup()
    {
        $this->assignGroup = null;
        return $this;
    }

    //-------------------------------------------------------------------------

    /**
     * Clears the role to assign to newly created users.
     *
     * @return $this
     */
    public function clearRole()
    {
        $this->assignRole = null;
        return $this;
    }

    //-------------------------------------------------------------------------

    /**
     * Creates a user.
     *
     * @param array $data User data
     *
     * @return mixed
     */
    public function createUser($data)
    {
        $validation = service('validation', null, false);
        $validation->setRules($this->validationRules, $this->validationMessages);

        if (!$validation->run($data)) {
            $this->error = $validation->getErrors();
            return false;
        }

        $id = $this->skipValidation()->insert($data);

        if (is_numeric($id)) return (int)$id;

        $this->error = $this->errors();

        return false;
    }

    //-------------------------------------------------------------------------

    /**
     * Deletes a user.
     *
     * @param int $teamId
     *
     * @return bool
     */
    public function deleteUser(int $id)
    {
        if (!$this->delete($id)) {
            $this->error = $this->errors();
            return false;
        }

        return true;
    }

    //-------------------------------------------------------------------------

    /**
     * Logs an activation attempt for posterity sake.
     *
     * @param string|null $token
     * @param string|null $ipAddress
     * @param string|null $userAgent
     */
    public function logActivationAttempt(string $token = null, string $ipAddress = null, string $userAgent = null)
    {
        $this->db->table('auth_activation_attempts')->insert([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    //-------------------------------------------------------------------------

    /**
     * Logs a password reset attempt for posterity sake.
     *
     * @param string $email
     * @param string|null $token
     * @param string|null $ipAddress
     * @param string|null $userAgent
     */
    public function logResetAttempt(string $email, string $token = null, string $ipAddress = null, string $userAgent = null)
    {
        $this->db->table('auth_reset_attempts')->insert([
            'email' => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    //-------------------------------------------------------------------------

    /**
     * Sets the group to assign when a user is created.
     *
     * @param string $groupName
     *
     * @return $this
     */
    public function withGroup(string $groupName)
    {
        $group = $this->db->table('auth_groups')->where('name', $groupName)->get()->getFirstRow();
        $this->assignGroup = $group->id;
        return $this;
    }

    //-------------------------------------------------------------------------

    /**
     * Sets the role to assign any users created.
     *
     * @param string $roleName
     *
     * @return $this
     */
    public function withRole(string $roleName)
    {
        $role = $this->db->table('auth_roles')->where('name', $roleName)->get()->getFirstRow();
        $this->assignRole = $role->id;
        return $this;
    }
}
