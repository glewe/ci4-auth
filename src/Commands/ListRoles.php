<?php

namespace CI4\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ListRoles extends BaseCommand
{

    protected $role       = 'Auth';
    protected $name        = 'auth:list_roles';
    protected $description = 'Lists roles from the database.';
    protected $usage       = 'auth:list_roles';

    public function run(array $params)
    {
        $db = db_connect();

        // get all roles
        $rows = $db->table('auth_roles')
            ->select('id, name, description')
            ->orderBy('name', 'asc')
            ->get()->getResultArray();

        if (empty($rows)) {
            CLI::write(CLI::color("There are no roles.", 'yellow'));
        } else {
            $thead = ['Role ID', 'Name', 'Description'];
            CLI::table($rows, $thead);
        }
    }
}
