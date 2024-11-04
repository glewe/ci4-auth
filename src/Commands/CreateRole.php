<?php

namespace CI4\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateRole extends BaseCommand {

  protected $role = 'Auth';
  protected $name = 'auth:create_role';
  protected $description = "Adds a new role to the database.";

  protected $usage = "auth:create_role [name] [description]";
  protected $arguments = [
    'name' => "The name of the new role to create",
    'description' => "Optional description 'in quotes'",
  ];

  /**
   * --------------------------------------------------------------------------
   * Run.
   * --------------------------------------------------------------------------
   *
   * This method is responsible for creating a new role in the system.
   * It takes an array of parameters as input, which should contain the role's
   * name and description.
   * If the name is not provided, it prompts the user to enter it.
   * If the description is not provided, it prompts the user to enter it.
   * It then attempts to create a new role with the provided name and description.
   * If the creation is successful, it outputs a success message.
   * If the creation fails, it outputs an error message.
   *
   * @param array $params An array of parameters. The first element should be the role's name and the second element should be the role's description.
   */
  public function run(array $params = []) {
    $auth = service('authorization');

    // consume or prompt for role name
    $name = array_shift($params);
    if (empty($name)) {
      $name = CLI::prompt('Role name', null, 'required');
    }

    // consume or prompt for description
    $description = array_shift($params);
    if (empty($description)) {
      $description = CLI::prompt('Description', '');
    }

    try {
      if (!$auth->createRole($name, $description)) {
        foreach ($auth->error() as $message) {
          CLI::write($message, 'red');
        }
      }
    } catch (\Exception $e) {
      $this->showError($e);
    }

    $this->call('auth:list_roles');
  }
}
