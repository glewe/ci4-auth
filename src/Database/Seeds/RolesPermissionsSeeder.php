<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    //-------------------------------------------------------------------------
    /**
     * Seed the roles_permissions table.
     */
    public function run()
    {
        $records = array(
            ['role_id' => 1, 'permission_id' => 1],
            ['role_id' => 1, 'permission_id' => 2],
            ['role_id' => 1, 'permission_id' => 3],
            ['role_id' => 1, 'permission_id' => 4],
            ['role_id' => 1, 'permission_id' => 5],
            ['role_id' => 1, 'permission_id' => 10],
            ['role_id' => 1, 'permission_id' => 11],
            ['role_id' => 1, 'permission_id' => 12],
            ['role_id' => 1, 'permission_id' => 13],
            ['role_id' => 1, 'permission_id' => 14],
            ['role_id' => 1, 'permission_id' => 15],
            ['role_id' => 1, 'permission_id' => 16],
            ['role_id' => 1, 'permission_id' => 17],
            ['role_id' => 2, 'permission_id' => 2],
            ['role_id' => 2, 'permission_id' => 3],
            ['role_id' => 2, 'permission_id' => 4],
            ['role_id' => 2, 'permission_id' => 5],
            ['role_id' => 2, 'permission_id' => 14],
            ['role_id' => 2, 'permission_id' => 15],
            ['role_id' => 2, 'permission_id' => 16],
            ['role_id' => 2, 'permission_id' => 17],
            ['role_id' => 3, 'permission_id' => 5],
            ['role_id' => 3, 'permission_id' => 17],
        );

        //
        // Simple Queries
        //
        // $this->db->query("INSERT INTO users (username, email) VALUES(:username:, :email:)", $data);

        //
        // Insert records
        //
        foreach ($records as $record) {
            $this->db->table('auth_roles_permissions')->insert($record);
        }
    }
}
