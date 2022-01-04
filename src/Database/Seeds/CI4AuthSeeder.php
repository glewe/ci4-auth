<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CI4AuthSeeder extends Seeder
{
    //-------------------------------------------------------------------------
    /**
     * Seed the CI4-Auth tables with sample data.
     */
    public function run()
    {

        $this->call('UserSeeder');
        $this->call('PermissionSeeder');
        $this->call('GroupSeeder');
        $this->call('RoleSeeder');
        $this->call('GroupsUsersSeeder');
    }
}
