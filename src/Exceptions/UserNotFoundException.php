<?php

namespace CI4\Auth\Exceptions;

class UserNotFoundException extends \RuntimeException implements ExceptionInterface
{
    public static function forUserID(int $id)
    {
        return new self(lang('Auth.user.not_found', [$id]), 404);
    }
}
