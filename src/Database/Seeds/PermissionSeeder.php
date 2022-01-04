<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionSeeder extends Seeder
{
    //-------------------------------------------------------------------------
    /**
     * Seed the 'permissions' table.
     */
    public function run()
    {
        $records = array(
            ['name' => 'Manage Application', 'description' => 'Manage all settings of the application'],
            ['name' => 'Manage Groups',      'description' => 'Create and edit groups and assign permissions to them'],
            ['name' => 'Manage Permissions', 'description' => 'Create and edit permissions'],
            ['name' => 'Manage Roles',       'description' => 'Create and edit roles and assign permissions to them'],
            ['name' => 'Manage Users',       'description' => 'Create and edit users and assign permissions to them'],
            ['name' => 'View Groups',        'description' => 'View the group list'],
            ['name' => 'View Permissions',   'description' => 'View the permission list'],
            ['name' => 'View Roles',         'description' => 'View the role list'],
            ['name' => 'View Users',         'description' => 'View the user list'],
        );

        // Simple Queries
        // $this->db->query("INSERT INTO users (username, email) VALUES(:username:, :email:)", $data);

        // Using Query Builder
        foreach ($records as $record) {

            $this->db->table('auth_permissions')->insert($record);
        }
    }
}
