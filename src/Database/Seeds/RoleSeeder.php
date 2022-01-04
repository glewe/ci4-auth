<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    //-------------------------------------------------------------------------
    /**
     * Seed the 'roles' table.
     */
    public function run()
    {
        $records = array(
            ['name' => 'Administrator', 'description' => 'Administrators with all permissions'],
            ['name' => 'Manager',       'description' => 'Logged in users with extended permissions'],
            ['name' => 'User',          'description' => 'Logged in users'],
            ['name' => 'Public',        'description' => 'Not logged in users'],
        );

        // Simple Queries
        // $this->db->query("INSERT INTO users (username, email) VALUES(:username:, :email:)", $data);

        // Using Query Builder
        foreach ($records as $record) {

            $this->db->table('auth_roles')->insert($record);
        }
    }
}
