<?php

namespace CI4\Auth\Authentication\Activators;

use CI4\Auth\Entities\User;

class UserActivator extends BaseActivator implements ActivatorInterface {
  /**
   * Sends activation message to the user via specified class in
   * `$requireActivation` setting in Config\Auth.php.
   *
   * @param User $user
   *
   * @return bool
   */
  public function send(User $user = null): bool {
    if (!$this->config->requireActivation) return true;

    $className = $this->config->requireActivation;

    $class = new $className();
    $class->setConfig($this->config);

    if ($class->send($user) === false) {
      log_message('error', "Failed to send activation messaage to: {$user->email}");
      $this->error = $class->error();
      return false;
    }

    return true;
  }
}
