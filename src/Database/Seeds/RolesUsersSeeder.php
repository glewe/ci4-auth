<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesUsersSeeder extends Seeder
{
    //-------------------------------------------------------------------------
    /**
     * Seed the 'groups_users' table.
     */
    public function run()
    {
        $records = array(
            ['role_id' => 1, 'user_id' => 1],
        );

        // Simple Queries
        // $this->db->query("INSERT INTO users (username, email) VALUES(:username:, :email:)", $data);

        // Using Query Builder
        foreach ($records as $record) {

            $this->db->table('auth_roles_users')->insert($record);
        }
    }
}
