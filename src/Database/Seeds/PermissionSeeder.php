<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionSeeder extends Seeder {
  //---------------------------------------------------------------------------
  /**
   * Seed the 'permissions' table.
   */
  public function run() {
    $records = array(
      [ 'name' => 'application.manage', 'description' => 'Allows to manage all settings of the application' ],
      [ 'name' => 'groups.create', 'description' => 'Allows to create groups' ],
      [ 'name' => 'groups.delete', 'description' => 'Allows to delete groups' ],
      [ 'name' => 'groups.edit', 'description' => 'Allows to edit groups' ],
      [ 'name' => 'groups.view', 'description' => 'Allows to view groups' ],
      [ 'name' => 'permissions.create', 'description' => 'Allows to create permissions' ],
      [ 'name' => 'permissions.delete', 'description' => 'Allows to delete permissions' ],
      [ 'name' => 'permissions.edit', 'description' => 'Allows to edit permissions' ],
      [ 'name' => 'permissions.view', 'description' => 'Allows to view permissions' ],
      [ 'name' => 'roles.create', 'description' => 'Allows to create roles' ],
      [ 'name' => 'roles.delete', 'description' => 'Allows to delete roles' ],
      [ 'name' => 'roles.edit', 'description' => 'Allows to edit roles' ],
      [ 'name' => 'roles.view', 'description' => 'Allows to view roles' ],
      [ 'name' => 'users.create', 'description' => 'Allows to create users' ],
      [ 'name' => 'users.delete', 'description' => 'Allows to delete users' ],
      [ 'name' => 'users.edit', 'description' => 'Allows to edit users' ],
      [ 'name' => 'users.view', 'description' => 'Allows to view users' ],
    );

    //
    // Simple Queries
    //
    // $this->db->query("INSERT INTO users (username, email) VALUES(:username:, :email:)", $data);

    //
    // Insert records
    //
    foreach ($records as $record) {
      $this->db->table('auth_permissions')->insert($record);
    }
  }
}
