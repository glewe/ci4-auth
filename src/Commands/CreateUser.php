<?php

namespace CI4\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CI4\Auth\Entities\User;
use CI4\Auth\Models\UserModel;

class CreateUser extends BaseCommand {
  protected $role = 'Auth';
  protected $name = 'auth:create_user';
  protected $description = "Adds a new user to the database.";

  protected $usage = "auth:create_user [username] [email]";
  protected $arguments = [
    'username' => "The username of the new user to create",
    'email' => "The email address of the new user to create",
  ];

  //---------------------------------------------------------------------------
  /**
   * This method is responsible for creating a new user in the system.
   * It takes an array of parameters as input, which should contain the user's username and email.
   * If the username is not provided, it prompts the user to enter it.
   * If the email is not provided, it prompts the user to enter it.
   * It then attempts to create a new user with the provided username and email.
   * The user is initially set as active and a random password is generated for the user.
   * The user data is then passed through the User entity and inserted into the UserModel.
   * If the insertion is successful, it outputs a success message with the username and user ID.
   * If the insertion fails, it outputs the error messages.
   *
   * @param array $params An array of parameters. The first element should be the user's username and the second element should be the user's email.
   */
  public function run(array $params = []) {
    // Start with the fields required for the account to be usable
    $row = [
      'active' => 1,
      'password' => bin2hex(random_bytes(24)),
    ];

    // Consume or prompt for username
    $row[ 'username' ] = array_shift($params);
    if (empty($row[ 'username' ])) {
      $row[ 'username' ] = CLI::prompt('Username', null, 'required');
    }

    // Consume or prompt for email
    $row[ 'email' ] = array_shift($params);
    if (empty($row[ 'email' ])) {
      $row[ 'email' ] = CLI::prompt('Email', null, 'required');
    }

    // Run the user through the entity and insert it
    $user = new User($row);

    $users = model(UserModel::class);
    if ($userId = $users->insert($user)) {
      CLI::write(lang('Auth.register.create_success', [ $row[ 'username' ], $userId ]), 'green');
    } else {
      foreach ($users->errors() as $message) {
        CLI::write($message, 'red');
      }
    }
  }
}
