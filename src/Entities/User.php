<?php

namespace CI4\Auth\Entities;

use CodeIgniter\Entity\Entity;
use CI4\Auth\Authorization\GroupModel;
use CI4\Auth\Authorization\RoleModel;
use CI4\Auth\Authorization\PermissionModel;
use CI4\Auth\Password;
use CI4\Auth\Models\UserOptionModel;

class User extends Entity {
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
  protected $dates = [ 'reset_at', 'reset_expires', 'created_at', 'updated_at', 'deleted_at' ];

  /**
   * Array of field names and the type of value to cast them as
   * when they are accessed.
   */
  protected $casts = [
    'active' => 'boolean',
    'force_pass_reset' => 'boolean',
  ];

  /**
   * Per-user permissions cache
   *
   * @var array
   */
  protected $permissions = [];

  /**
   * Per-user personal permissions cache
   *
   * @var array
   */
  protected $personalPermissions = [];

  /**
   * Per-user groups cache
   *
   * @var array
   */
  protected $groups = [];

  /**
   * Per-user options cache
   *
   * @var array
   */
  protected $options = [];

  /**
   * Per-user roles cache
   *
   * @var array
   */
  protected $roles = [];

  /**
   * --------------------------------------------------------------------------
   * Activate.
   * --------------------------------------------------------------------------
   *
   * Activate user.
   *
   * @return $this
   */
  public function activate(): User {
    $this->attributes['active'] = 1;
    $this->attributes['activate_hash'] = null;
    return $this;
  }

  /**
   * --------------------------------------------------------------------------
   * Ban.
   * --------------------------------------------------------------------------
   *
   * Bans a user.
   *
   * @param string $reason
   *
   * @return $this
   */
  public function ban(string $reason): User {
    $this->attributes['status'] = 'banned';
    $this->attributes['status_message'] = $reason;
    return $this;
  }

  /**
   * --------------------------------------------------------------------------
   * Can.
   * --------------------------------------------------------------------------
   *
   * Determines whether the user has the appropriate permission, either
   * directly, or through a group or role.
   *
   * @param string $permission
   *
   * @return bool
   */
  public function can(string $permission): bool {
    return in_array(strtolower($permission), $this->getPermissions());
  }

  /**
   * --------------------------------------------------------------------------
   * Deactivate.
   * --------------------------------------------------------------------------
   *
   * Deactivate user.
   *
   * @return $this
   */
  public function deactivate(): User {
    $this->attributes['active'] = 0;
    return $this;
  }

  /**
   * --------------------------------------------------------------------------
   * Force Password Reset.
   * --------------------------------------------------------------------------
   *
   * Force a user to reset their password on next page refresh
   * or login. Checked in the LocalAuthenticator's check() method.
   *
   * @return $this
   *
   * @throws \Exception
   */
  public function forcePasswordReset(): User {
    $this->generateResetHash();
    $this->attributes['force_pass_reset'] = 1;
    return $this;
  }

  /**
   * --------------------------------------------------------------------------
   * Generate Activate Hash.
   * * --------------------------------------------------------------------------
   * *
   * Generates a secure random hash to use for account activation.
   *
   * @return $this
   *
   * @throws \Exception
   */
  public function generateActivateHash(): User {
    $this->attributes['activate_hash'] = bin2hex(random_bytes(16));
    return $this;
  }

  /**
   * --------------------------------------------------------------------------
   * Generate Reset Hash.
   * --------------------------------------------------------------------------
   *
   * Generates a secure hash to use for password reset purposes,
   * saves it to the instance.
   *
   * @return $this
   *
   * @throws \Exception
   */
  public function generateResetHash(): User {
    $this->attributes['reset_hash'] = bin2hex(random_bytes(16));
    $this->attributes['reset_expires'] = date('Y-m-d H:i:s', time() + config('Auth')->resetTime);
    return $this;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Attribute.
   * --------------------------------------------------------------------------
   *
   * Returns a single attribute value.
   *
   * @param string $attr
   */
  public function getAttribute($attr): string {
    return $this->attributes[$attr];
  }

  /**
   * --------------------------------------------------------------------------
   * Get Attributes.
   * --------------------------------------------------------------------------
   *
   * Returns an array of all attributes.
   *
   * @return array
   */
  public function getAttributes(): array {
    return $this->attributes;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Groups.
   * --------------------------------------------------------------------------
   *
   * Returns the user's groups, formatted for simple checking:
   *
   * [
   *    id => name,
   *    id => name,
   * ]
   *
   * @return array
   */
  public function getGroups(): array {
    if (empty($this->id)) throw new \RuntimeException('Users must be created before getting groups.');

    if (empty($this->groups)) {
      $groups = model(GroupModel::class)->getGroupsForUser($this->id);
      foreach ($groups as $group) {
        $this->groups[$group['group_id']] = strtolower($group['name']);
      }
    }

    return $this->groups;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Option.
   * --------------------------------------------------------------------------
   *
   * Returns a specific user options.
   *
   * @param string $option Option to get
   *
   * @return string
   */
  public function getOption($option): string {
    if (empty($this->id)) throw new \RuntimeException('Users must be created before getting options.');

    return model(UserOptionModel::class)->getOption([
      'user_id' => $this->id,
      'option' => $option
    ]);
  }

  /**
   * --------------------------------------------------------------------------
   * Get Options.
   * --------------------------------------------------------------------------
   *
   * Returns all the user's options, formatted for simple checking:
   *
   * [
   *    option => value,
   *    option => value,
   * ]
   *
   * @return array
   */
  public function getOptions(): array {
    if (empty($this->id)) throw new \RuntimeException('Users must be created before getting options.');

    if (empty($this->options)) {
      $options = model(UserOptionModel::class)->getOptionsForUser($this->id);
      foreach ($options as $option) {
        $this->options[$option['option']] = $option['value'];
      }
    }

    return $this->options;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Permissions.
   * --------------------------------------------------------------------------
   *
   * Returns the user's permissions, formatted for simple checking:
   *
   * [
   *    id => name,
   *    id=> name,
   * ]
   *
   * @return array
   */
  public function getPermissions(): array {
    if (empty($this->id)) throw new \RuntimeException('Users must be created before getting permissions.');
    if (empty($this->permissions)) $this->permissions = model(PermissionModel::class)->getPermissionsForUser($this->id);
    return $this->permissions;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Personal Permissions.
   * --------------------------------------------------------------------------
   *
   * Returns the user's permissions, formatted for simple checking:
   *
   * [
   *    id => name,
   *    id=> name,
   * ]
   *
   * @return array
   */
  public function getPersonalPermissions(): array {
    if (empty($this->id)) throw new \RuntimeException('Users must be created before getting permissions.');
    if (empty($this->personalPermissions)) $this->personalPermissions = model(PermissionModel::class)->getPersonalPermissionsForUser($this->id);
    return $this->personalPermissions;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Roles.
   * --------------------------------------------------------------------------
   *
   * Returns the user's roles, formatted for simple checking:
   *
   * [
   *    id => name,
   *    id => name,
   * ]
   *
   * @return array
   */
  public function getRoles(): array {
    if (empty($this->id)) throw new \RuntimeException('Users must be created before getting roles.');

    if (empty($this->roles)) {
      $roles = model(RoleModel::class)->getRolesForUser($this->id);
      foreach ($roles as $role) {
        $this->roles[$role['role_id']] = strtolower($role['name']);
      }
    }

    return $this->roles;
  }

  /**
   * --------------------------------------------------------------------------
   * Has Secret.
   * --------------------------------------------------------------------------
   *
   * Checks to see if a user has a secret hash (2FA setup).
   *
   * @return bool
   */
  public function hasSecret(): bool {
    return isset($this->attributes['secret_hash']) && $this->attributes['secret_hash'] != '';
  }

  /**
   * --------------------------------------------------------------------------
   * Is Activated.
   * --------------------------------------------------------------------------
   *
   * Checks to see if a user is active.
   *
   * @return bool
   */
  public function isActivated(): bool {
    return isset($this->attributes['active']) && $this->attributes['active'] == true;
  }

  /**
   * --------------------------------------------------------------------------
   * Is Banned.
   * --------------------------------------------------------------------------
   *
   * Checks to see if a user has been banned.
   *
   * @return bool
   */
  public function isBanned(): bool {
    return isset($this->attributes['status']) && $this->attributes['status'] === 'banned';
  }

  /**
   * --------------------------------------------------------------------------
   * Set Attribute.
   * --------------------------------------------------------------------------
   *
   * Sets a single attribute value.
   *
   * @param string $attr
   * @param string $val
   *
   * @return void
   */
  public function setAttribute($attr, $val): void {
    $this->attributes[$attr] = $val;
  }

  /**
   * --------------------------------------------------------------------------
   * Set Password.
   * --------------------------------------------------------------------------
   *
   * Automatically hashes the password when set.
   *
   * @see https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence
   *
   * @param string $password
   *
   * @return void
   */
  public function setPassword(string $password): void {
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

  /**
   * --------------------------------------------------------------------------
   * Set Permissions.
   * --------------------------------------------------------------------------
   *
   * Warns the developer it won't work, so they don't spend hours tracking stuff down.
   *
   * @param array $permissions
   *
   * @return void
   *
   * @throws \RuntimeException
   */
  public function setPermissions(array $permissions = null): void {
    throw new \RuntimeException('User entity does not support saving permissions directly.');
  }

  /**
   * --------------------------------------------------------------------------
   * Set Secret.
   * --------------------------------------------------------------------------
   *
   * Sets the secret hash.
   *
   * @param string $secret
   *
   * @return void
   */
  public function setSecret(string $secret): void {
    $this->attributes['secret_hash'] = $secret;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Secret.
   * --------------------------------------------------------------------------
   *
   * Gets the secret hash.
   *
   * @return string
   */
  public function getSecret(): string {
    return $this->attributes['secret_hash'];
  }

  /**
   * --------------------------------------------------------------------------
   * Set Secret.
   * --------------------------------------------------------------------------
   *
   * Removes the secret hash.
   *
   * @return void
   */
  public function removeSecret(): void {
    $this->attributes['secret_hash'] = '';
  }

  /**
   * --------------------------------------------------------------------------
   * Unban.
   * --------------------------------------------------------------------------
   *
   * Removes a ban from a user.
   *
   * @return $this
   */
  public function unBan(): User {
    $this->attributes['status'] = $this->status_message = '';
    return $this;
  }
}
