<?php

namespace CI4\Auth\Authentication\Resetters;

use CI4\Auth\Entities\User;

class UserResetter extends BaseResetter implements ResetterInterface
{
    /**
     * Sends reset message to the user via specified class
     * in `$activeResetter` setting in Config\Auth.php.
     *
     * @param User $user
     *
     * @return bool
     */
    public function send(User $user = null): bool
    {
        if ($this->config->activeResetter === null) {
            return true;
        }

        $className = $this->config->activeResetter;

        $class = new $className();
        $class->setConfig($this->config);

        if ($class->send($user) === false) {
            log_message('error', lang('Auth.forgot.error_reset', [$user->username]));
            $this->error = $class->error();

            return false;
        }

        return true;
    }
}
