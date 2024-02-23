<?php

namespace CI4\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CI4\Auth\Models\UserModel;

class ActivateUser extends BaseCommand {
  protected $role = 'Auth';
  protected $name = 'auth:activate_user';
  protected $description = 'Activate Existing User.';

  protected $usage = 'auth:activate_user [identity]';
  protected $arguments = [
    'identity' => 'User identity.',
  ];

  //---------------------------------------------------------------------------
  /**
   * This method is responsible for activating a user in the system.
   * It takes an array of parameters as input, which should contain the user's identity.
   * If the identity is not provided, it prompts the user to enter it.
   * The identity can be either an email or a username.
   * It then checks if a user with the provided identity exists in the system.
   * If the user exists, it sets the 'active' field of the user to 1, indicating that the user is activated.
   * If the user does not exist, it outputs an error message.
   * If the activation is successful, it outputs a success message.
   * If the activation fails, it outputs a failure message.
   *
   * @param array $params An array of parameters. The first element should be the user's identity.
   */
  public function run(array $params = []) {
    // Consume or prompt for password
    $identity = array_shift($params);

    if (empty($identity)) {
      $identity = CLI::prompt('Identity', null, 'required');
    }

    $type = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    $userModel = new UserModel();
    $user = $userModel->where($type, $identity)->first();

    if (!$user) {
      CLI::write('User with identity: ' . $identity . ' not found.', 'red');
    } else {
      $user->active = 1;

      if ($userModel->save($user)) {
        CLI::write('Sucessfuly activated the user with identity: ' . $identity, 'green');
      } else {
        CLI::write('Failed to activate the user with identity: ' . $identity, 'red');
      }
    }
  }
}
