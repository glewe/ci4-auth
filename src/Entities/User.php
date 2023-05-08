<?php

namespace CI4\Auth\Entities;

use CodeIgniter\Entity\Entity;
use CI4\Auth\Authorization\GroupModel;
use CI4\Auth\Authorization\RoleModel;
use CI4\Auth\Authorization\PermissionModel;
use CI4\Auth\Password;
use CI4\Auth\Models\UserOptionModel;

class User extends Entity
{
    /**
     * Maps names used in sets and gets against unique
     * names within the class, allowing independence from
     * database column names.
     *
     * Example:
     *  $datamap = [
     *      'db_name' => 'class_name'
     *  ];
     */
    protected $datamap = [];

    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['reset_at', 'reset_expires', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [
        'active'           => 'boolean',
        'force_pass_reset' => 'boolean',
    ];

    /**
     * Per-user permissions cache
     * @var array
     */
    protected $permissions = [];

    /**
     * Per-user personal permissions cache
     * @var array
     */
    protected $personalPermissions = [];

    /**
     * Per-user groups cache
     * @var array
     */
    protected $groups = [];

    /**
     * Per-user options cache
     * @var array
     */
    protected $options = [];

    /**
     * Per-user roles cache
     * @var array
     */
    protected $roles = [];

    //-------------------------------------------------------------------------
    /**
     * Activate user.
     *
     * @return $this
     */
    public function activate()
    {
        $this->attributes['active'] = 1;
        $this->attributes['activate_hash'] = null;
        return $this;
    }

    //-------------------------------------------------------------------------
    /**
     * Bans a user.
     *
     * @param string $reason
     *
     * @return $this
     */
    public function ban(string $reason)
    {
        $this->attributes['status'] = 'banned';
        $this->attributes['status_message'] = $reason;
        return $this;
    }

    //-------------------------------------------------------------------------
    /**
     * Determines whether the user has the appropriate permission, either 
     * directly, or through one of it's roles.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function can(string $permission)
    {
        return in_array(strtolower($permission), $this->getPermissions());
    }

    //-------------------------------------------------------------------------
    /**
     * Deactivate user.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->attributes['active'] = 0;
        return $this;
    }

    //-------------------------------------------------------------------------
    /**
     * Force a user to reset their password on next page refresh
     * or login. Checked in the LocalAuthenticator's check() method.
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function forcePasswordReset()
    {
        $this->generateResetHash();
        $this->attributes['force_pass_reset'] = 1;
        return $this;
    }

    //-------------------------------------------------------------------------
    /**
     * Generates a secure random hash to use for account activation.
     *
     * @return $this
     * @throws \Exception
     */
    public function generateActivateHash()
    {
        $this->attributes['activate_hash'] = bin2hex(random_bytes(16));
        return $this;
    }

    //-------------------------------------------------------------------------
    /**
     * Generates a secure hash to use for password reset purposes,
     * saves it to the instance.
     *
     * @return $this
     * @throws \Exception
     */
    public function generateResetHash()
    {
        $this->attributes['reset_hash'] = bin2hex(random_bytes(16));
        $this->attributes['reset_expires'] = date('Y-m-d H:i:s', time() + config('Auth')->resetTime);
        return $this;
    }

    //-------------------------------------------------------------------------
    /**
     * Returns a single attribute value.
     *
     */
    public function getAttribute($attr)
    {
        return $this->attributes[$attr];
    }

    //-------------------------------------------------------------------------
    /**
     * Returns a single attribute value.
     *
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    //-------------------------------------------------------------------------
    /**
     * Returns the user's groups, formatted for simple checking:
     *
     * [
     *    id => name,
     *    id => name,
     * ]
     *
     * @return array|mixed
     */
    public function getGroups()
    {
        if (empty($this->id)) throw new \RuntimeException('Users must be created before getting groups.');

        if (empty($this->groups)) {
            $groups = model(GroupModel::class)->getGroupsForUser($this->id);
            foreach ($groups as $group) {
                $this->groups[$group['group_id']] = strtolower($group['name']);
            }
        }

        return $this->groups;
    }

    //-------------------------------------------------------------------------
    /**
     * Returns a specific user options.
     *
     * @param string $option Option to get
     * 
     * @return string
     */
    public function getOption($option)
    {
        if (empty($this->id)) throw new \RuntimeException('Users must be created before getting options.');

        return model(UserOptionModel::class)->getOption([
            'user_id' => $this->id,
            'option' => $option
        ]);
    }

    //-------------------------------------------------------------------------
    /**
     * Returns the user's options, formatted for simple checking:
     *
     * [
     *    option => value,
     *    option => value,
     * ]
     *
     * @return array|mixed
     */
    public function getOptions()
    {
        if (empty($this->id)) throw new \RuntimeException('Users must be created before getting options.');

        if (empty($this->options)) {
            $options = model(UserOptionModel::class)->getOptionsForUser($this->id);
            foreach ($options as $option) {
                $this->options[$option['option']] = $option['value'];
            }
        }

        return $this->options;
    }

    //-------------------------------------------------------------------------
    /**
     * Returns the user's permissions, formatted for simple checking:
     *
     * [
     *    id => name,
     *    id=> name,
     * ]
     *
     * @return array|mixed
     */
    public function getPermissions()
    {
        if (empty($this->id)) throw new \RuntimeException('Users must be created before getting permissions.');
        if (empty($this->permissions)) $this->permissions = model(PermissionModel::class)->getPermissionsForUser($this->id);
        return $this->permissions;
    }

    //-------------------------------------------------------------------------
    /**
     * Returns the user's permissions, formatted for simple checking:
     *
     * [
     *    id => name,
     *    id=> name,
     * ]
     *
     * @return array|mixed
     */
    public function getPersonalPermissions()
    {
        if (empty($this->id)) throw new \RuntimeException('Users must be created before getting permissions.');
        if (empty($this->personalPermissions)) $this->personalPermissions = model(PermissionModel::class)->getPersonalPermissionsForUser($this->id);
        return $this->personalPermissions;
    }

    //-------------------------------------------------------------------------
    /**
     * Returns the user's roles, formatted for simple checking:
     *
     * [
     *    id => name,
     *    id => name,
     * ]
     *
     * @return array|mixed
     */
    public function getRoles()
    {
        if (empty($this->id)) throw new \RuntimeException('Users must be created before getting roles.');

        if (empty($this->roles)) {
            $roles = model(RoleModel::class)->getRolesForUser($this->id);
            foreach ($roles as $role) {
                $this->roles[$role['role_id']] = strtolower($role['name']);
            }
        }

        return $this->roles;
    }

    //-------------------------------------------------------------------------
    /**
     * Checks to see if a user has a secret hash (2FA setup).
     *
     * @return bool
     */
    public function hasSecret(): bool
    {
        return isset($this->attributes['secret_hash']) && $this->attributes['secret_hash'] != '';
    }

    //-------------------------------------------------------------------------
    /**
     * Checks to see if a user is active.
     *
     * @return bool
     */
    public function isActivated(): bool
    {
        return isset($this->attributes['active']) && $this->attributes['active'] == true;
    }

    //-------------------------------------------------------------------------
    /**
     * Checks to see if a user has been banned.
     *
     * @return bool
     */
    public function isBanned(): bool
    {
        return isset($this->attributes['status']) && $this->attributes['status'] === 'banned';
    }

    //-------------------------------------------------------------------------
    /**
     * Sets a single attribute value.
     *
     */
    public function setAttribute($attr, $val)
    {
        $this->attributes[$attr] = $val;
    }

    //-------------------------------------------------------------------------
    /**
     * Automatically hashes the password when set.
     *
     * @see https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence
     *
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->attributes['password_hash'] = Password::hash($password);

        //
        // Set these vars to null in case a reset password was asked.
        //
        // Scenario:
        //    user (a *dumb* one with short memory) requests a reset-token and 
        //    then does nothing => asks the administrator to reset his password.
        //
        // User would have a new password but still anyone with the reset-token
        // would be able to change the password.
        //
        $this->attributes['reset_hash'] = null;
        $this->attributes['reset_at'] = null;
        $this->attributes['reset_expires'] = null;
    }

    //-------------------------------------------------------------------------
    /**
     * Warns the developer it won't work, so they don't spend hours tracking stuff down.
     *
     * @param array $permissions
     *
     * @return $this
     */
    public function setPermissions(array $permissions = null)
    {
        throw new \RuntimeException('User entity does not support saving permissions directly.');
    }

    //-------------------------------------------------------------------------
    /**
     * Encrypts the secret when set.
     *
     * @param string $secret
     */
    public function setSecret(string $secret)
    {
        $this->attributes['secret_hash'] = $secret;
    }

    //-------------------------------------------------------------------------
    /**
     * Encrypts the secret when set.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->attributes['secret_hash'];
    }

    //-------------------------------------------------------------------------
    /**
     * Removes the secret.
     */
    public function removeSecret()
    {
        $this->attributes['secret_hash'] = '';
    }

    //-------------------------------------------------------------------------
    /**
     * Removes a ban from a user.
     *
     * @return $this
     */
    public function unBan()
    {
        $this->attributes['status'] = $this->status_message = '';
        return $this;
    }
}
