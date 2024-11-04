<?php

namespace CI4\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CI4\Auth\Models\UserModel;

class SetPassword extends BaseCommand {
  protected $role = 'Auth';
  protected $name = 'auth:set_password';
  protected $description = 'Set password to user.';

  protected $usage = 'auth:set_password [identity] [password]';
  protected $arguments = [
    'identity' => 'User identity.',
    'password' => 'Password value you want to set.',
  ];

  /**
   * --------------------------------------------------------------------------
   * Run.
   * --------------------------------------------------------------------------
   *
   * This method is responsible for setting a new password for a user.
   * It takes an array of parameters as input, which should contain the user's identity and the new password.
   * If the identity is not provided, it prompts the user to enter it.
   * If the password is not provided, it prompts the user to enter it.
   * The identity can be either an email or a username.
   * It then checks if a user with the provided identity exists in the system.
   * If the user exists, it sets the 'password' field of the user to the provided password.
   * If the user does not exist, it outputs an error message.
   * If the password setting is successful, it outputs a success message.
   * If the password setting fails, it outputs a failure message.
   *
   * @param array $params An array of parameters. The first element should be the user's identity and the second element should be the new password.
   */
  public function run(array $params = []) {
    // Consume or prompt for password
    $identity = isset($params[0]) ? $params[0] : null;
    $password = isset($params[1]) ? $params[1] : null;

    if (empty($identity)) {
      $identity = CLI::prompt('Identity', null, 'required');
    }

    if (empty($password)) {
      $password = CLI::prompt('Password', null, 'required');
    }

    $type = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    $userModel = new UserModel();
    $user = $userModel->where($type, $identity)->first();

    if (!$user) {
      CLI::write('User with identity: ' . $identity . ' not found.', 'red');
    } else {
      $user->password = $password;

      if ($userModel->save($user)) {
        CLI::write('Password successfully set for user with identity: ' . $identity, 'green');
      } else {
        CLI::write('Failed to set password for user with identity: ' . $identity, 'red');
      }
    }
  }
}
