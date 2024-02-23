<?php

namespace CI4\Auth\Authentication\Resetters;

use CI4\Auth\Entities\User;

/**
 * Interface ResetterInterface
 *
 * @package CI4\Auth\Authentication\Resetters
 */
interface ResetterInterface {
  /**
   * Send reset message to user
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
