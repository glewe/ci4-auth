<?php

namespace CI4\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ListRoles extends BaseCommand {

  protected $role = 'Auth';
  protected $name = 'auth:list_roles';
  protected $description = 'Lists roles from the database.';
  protected $usage = 'auth:list_roles';

  //---------------------------------------------------------------------------
  /**
   * This method is responsible for listing all the roles from the database.
   * It does not require any parameters.
   * It first establishes a connection to the database.
   * Then, it selects the 'id', 'name', and 'description' fields from the 'auth_roles' table and orders the results by 'name' in ascending order.
   * The results are then fetched as an array.
   * If there are no roles in the database, it outputs a message saying "There are no roles."
   * If there are roles, it outputs a table with the 'Role ID', 'Name', and 'Description' of each role.
   */
  public function run(array $params) {
    $db = db_connect();

    // get all roles
    $rows = $db->table('auth_roles')
      ->select('id, name, description')
      ->orderBy('name', 'asc')
      ->get()->getResultArray();

    if (empty($rows)) {
      CLI::write(CLI::color("There are no roles.", 'yellow'));
    } else {
      $thead = [ 'Role ID', 'Name', 'Description' ];
      CLI::table($rows, $thead);
    }
  }
}
