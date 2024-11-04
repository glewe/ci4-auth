<?php

namespace CI4\Auth\Authentication\Resetters;

use CI4\Auth\Config\Auth as AuthConfig;
use CI4\Auth\Entities\User;

abstract class BaseResetter {
  /**
   * @var AuthConfig
   */
  protected $config;

  /**
   * @var string
   */
  protected $error = '';

  /**
   * Sends a reset message to user
   *
   * @param User $user
   *
   * @return bool
   */
  abstract public function send(User $user = null): bool;

  /**
   * Sets the initial config file.
   *
   * @param AuthConfig|null $config
   */
  public function __construct(AuthConfig $config = null) {
    $this->config = $config ?? config('Auth');
  }

  /**
   * Allows for changing the config file on the Resetter.
   *
   * @param AuthConfig $config
   *
   * @return $this
   */
  public function setConfig(AuthConfig $config) {
    $this->config = $config;

    return $this;
  }

  /**
   * Gets a config settings for current Resetter.
   *
   * @return object
   */
  public function getResetterSettings() {
    return (object)$this->config->userResetters[static::class];
  }

  /**
   * Returns the current error.
   *
   * @return string
   */
  public function error(): string {
    return $this->error;
  }
}
