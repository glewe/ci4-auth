<?php

namespace CI4\Auth\Exceptions;

class UserNotFoundException extends \RuntimeException implements ExceptionInterface {
  //---------------------------------------------------------------------------
  /**
   * This static method is responsible for creating a new instance of the UserNotFoundException.
   * It takes an integer as input, which should be the user's ID.
   * The method then calls the lang function with the 'Auth.user.not_found' key and the user's ID as parameters.
   * The lang function is expected to return a localized string with the user's ID inserted at the appropriate place.
   * This localized string is then used as the message for the new UserNotFoundException.
   * The exception's code is set to 404.
   *
   * @param int $id The ID of the user that was not found.
   * @return UserNotFoundException The newly created exception.
   */
  public static function forUserID(int $id) {
    return new self(lang('Auth.user.not_found', [ $id ]), 404);
  }
}
