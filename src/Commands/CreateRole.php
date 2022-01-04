<?php

namespace CI4\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateRole extends BaseCommand
{

    protected $role       = 'Auth';
    protected $name        = 'auth:create_role';
    protected $description = "Adds a new role to the database.";

    protected $usage     = "auth:create_role [name] [description]";
    protected $arguments = [
        'name'        => "The name of the new role to create",
        'description' => "Optional description 'in quotes'",
    ];

    public function run(array $params = [])
    {
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
