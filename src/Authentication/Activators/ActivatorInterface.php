<?php

namespace CI4\Auth\Authentication\Activators;

use CI4\Auth\Entities\User;

/**
 * Interface ActivatorInterface
 *
 * @package CI4\Auth\Authentication\Activators
 */
interface ActivatorInterface {
  /**
   * Send activation message to user
   *
   * @param User $user
   *
   * @return bool
   */
  public function send(User $user = null): bool;

  /**
   * Returns the error string that should be displayed to the user.
   *
   * @return string
   */
  public function error(): string;
}
