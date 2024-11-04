<?php

namespace CI4\Auth\Authentication\Activators;

use CI4\Auth\Config\Auth as AuthConfig;
use CI4\Auth\Entities\User;

abstract class BaseActivator {
  /**
   * @var AuthConfig
   */
  protected $config;

  /**
   * @var string
   */
  protected $error = '';

  /**
   * Sends an activation message to user
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
   * Allows for changing the config file on the Activator.
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
   * Gets a config settings for current Activator.
   *
   * @return object
   */
  public function getActivatorSettings() {
    return (object)$this->config->userActivators[static::class];
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
