<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupSeeder extends Seeder
{
    //-------------------------------------------------------------------------
    /**
     * Seed the 'groups' table.
     */
    public function run()
    {
        $records = array(
            ['name' => 'Admins', 'description' => 'Application administrators'],
            ['name' => 'Disney', 'description' => 'Disney characters'],
            ['name' => 'Pixar',  'description' => 'Pixar characters'],
            ['name' => 'Looney', 'description' => 'Looney characters'],
        );

        // Simple Queries
        // $this->db->query("INSERT INTO users (username, email) VALUES(:username:, :email:)", $data);

        // Using Query Builder
        foreach ($records as $record) {

            $this->db->table('auth_groups')->insert($record);
        }
    }
}
