<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesUsersSeeder extends Seeder
{
    //-------------------------------------------------------------------------
    /**
     * Seed the 'roles_users' table.
     */
    public function run()
    {
        $records = array(
            ['role_id' => 1, 'user_id' => 1],
            ['role_id' => 2, 'user_id' => 2],
            ['role_id' => 3, 'user_id' => 3],
            ['role_id' => 3, 'user_id' => 4],
            ['role_id' => 3, 'user_id' => 5],
            ['role_id' => 3, 'user_id' => 6],
            ['role_id' => 3, 'user_id' => 7],
        );

        //
        // Simple Queries
        //
        // $this->db->query("INSERT INTO users (username, email) VALUES(:username:, :email:)", $data);

        //
        // Insert records
        //
        foreach ($records as $record) {
            $this->db->table('auth_roles_users')->insert($record);
        }
    }
}
