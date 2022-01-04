<?php

namespace CI4\Auth\Authentication\Activators;

use Config\Email;
use CI4\Auth\Entities\User;

/**
 * Class EmailActivator
 *
 * Sends an activation email to user.
 *
 * @package CI4\Auth\Authentication\Activators
 */
class EmailActivator extends BaseActivator implements ActivatorInterface
{
    /**
     * Sends an activation email
     *
     * @param User $user
     *
     * @return bool
     */
    public function send(User $user = null): bool
    {
        $email = service('email');
        $config = new Email();

        $settings = $this->getActivatorSettings();

        $sent = $email->setFrom($settings->fromEmail ?? $config->fromEmail, $settings->fromName ?? $config->fromName)
            ->setTo($user->email)
            ->setSubject(lang('Auth.activation.subject'))
            ->setMessage(view($this->config->views['emailActivation'], ['hash' => $user->activate_hash]))
            ->setMailType('html')
            ->send();

        if (!$sent) {

            $this->error = lang('Auth.activation.error_sending', [$user->email]);
            return false;
        }

        return true;
    }
}
