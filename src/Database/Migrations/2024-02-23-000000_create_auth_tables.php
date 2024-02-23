<?php

namespace CI4\Auth\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthTables extends Migration {
  //---------------------------------------------------------------------------
  /**
   * Create Tables
   */
  public function up() {
    //
    // Users Table
    //
    $this->forge->addField([
      'id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
      'email' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'username' => [ 'type' => 'varchar', 'constraint' => 80, 'null' => true ],
      'lastname' => [ 'type' => 'varchar', 'constraint' => 120 ],
      'firstname' => [ 'type' => 'varchar', 'constraint' => 120 ],
      'displayname' => [ 'type' => 'varchar', 'constraint' => 120 ],
      'password_hash' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'secret_hash' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'reset_hash' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true ],
      'reset_at' => [ 'type' => 'datetime', 'null' => true ],
      'reset_expires' => [ 'type' => 'datetime', 'null' => true ],
      'activate_hash' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true ],
      'status' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true ],
      'status_message' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true ],
      'active' => [ 'type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 0 ],
      'force_pass_reset' => [ 'type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 0 ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
      'deleted_at' => [ 'type' => 'datetime', 'null' => true ],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addUniqueKey('email');
    $this->forge->addUniqueKey('username');
    $this->forge->createTable('users', true);

    //
    // Users_Options Table
    //
    $fields = [
      'id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
      'user_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0 ],
      'option' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'value' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ];

    $this->forge->addField($fields);
    $this->forge->addKey('id', true);
    $this->forge->addKey([ 'user_id', 'option' ]);
    $this->forge->addForeignKey('user_id', 'users', 'id', '', 'CASCADE');
    $this->forge->createTable('users_options', true);

    //
    // Logins Table
    //
    $this->forge->addField([
      'id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
      'ip_address' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true ],
      'email' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true ],
      'user_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true ],
      'date' => [ 'type' => 'datetime' ],
      'success' => [ 'type' => 'tinyint', 'constraint' => 1 ],
      'info' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->addKey('email');
    $this->forge->addKey('user_id');
    // NOTE: Do NOT delete the user_id or email when the user is deleted for security audits
    $this->forge->createTable('auth_logins', true);

    //
    // Tokens Table
    // @see https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence
    //
    $this->forge->addField([
      'id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
      'selector' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'hashedValidator' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'user_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true ],
      'expires' => [ 'type' => 'datetime' ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->addKey('selector');
    $this->forge->addForeignKey('user_id', 'users', 'id', '', 'CASCADE');
    $this->forge->createTable('auth_tokens', true);

    //
    // Reset Attempts Table
    //
    $this->forge->addField([
      'id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
      'email' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'ip_address' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'user_agent' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'token' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('auth_reset_attempts', true);

    //
    // Activation Attempts Table
    //
    $this->forge->addField([
      'id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
      'ip_address' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'user_agent' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'token' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('auth_activation_attempts', true);

    //
    // Roles Table
    //
    $fields = [
      'id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
      'name' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'description' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ];

    $this->forge->addField($fields);
    $this->forge->addKey('id', true);
    $this->forge->createTable('auth_roles', true);

    //
    // Permissions Table
    //
    $fields = [
      'id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
      'name' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'description' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ];

    $this->forge->addField($fields);
    $this->forge->addKey('id', true);
    $this->forge->createTable('auth_permissions', true);

    //
    // Roles_Permissions Table
    //
    $fields = [
      'role_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0 ],
      'permission_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0 ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ];

    $this->forge->addField($fields);
    $this->forge->addKey([ 'role_id', 'permission_id' ]);
    $this->forge->addForeignKey('role_id', 'auth_roles', 'id', '', 'CASCADE');
    $this->forge->addForeignKey('permission_id', 'auth_permissions', 'id', '', 'CASCADE');
    $this->forge->createTable('auth_roles_permissions', true);

    //
    // Roles_Users Table
    //
    $fields = [
      'role_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0 ],
      'user_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0 ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ];

    $this->forge->addField($fields);
    $this->forge->addKey([ 'role_id', 'user_id' ]);
    $this->forge->addForeignKey('role_id', 'auth_roles', 'id', '', 'CASCADE');
    $this->forge->addForeignKey('user_id', 'users', 'id', '', 'CASCADE');
    $this->forge->createTable('auth_roles_users', true);

    //
    // Users_Permissions Table
    //
    $fields = [
      'user_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0 ],
      'permission_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0 ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ];

    $this->forge->addField($fields);
    $this->forge->addKey([ 'user_id', 'permission_id' ]);
    $this->forge->addForeignKey('user_id', 'users', 'id', '', 'CASCADE');
    $this->forge->addForeignKey('permission_id', 'auth_permissions', 'id', '', 'CASCADE');
    $this->forge->createTable('auth_users_permissions', true);

    //
    // Groups Table
    //
    $fields = [
      'id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
      'name' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'description' => [ 'type' => 'varchar', 'constraint' => 255 ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ];

    $this->forge->addField($fields);
    $this->forge->addKey('id', true);
    $this->forge->createTable('auth_groups', true);

    //
    // Groups_Users Table
    //
    $fields = [
      'group_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0 ],
      'user_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0 ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ];

    $this->forge->addField($fields);
    $this->forge->addKey([ 'group_id', 'user_id' ]);
    $this->forge->addForeignKey('group_id', 'auth_groups', 'id', '', 'CASCADE');
    $this->forge->addForeignKey('user_id', 'users', 'id', '', 'CASCADE');
    $this->forge->createTable('auth_groups_users', true);

    //
    // Groups_Permissions Table
    //
    $fields = [
      'group_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0 ],
      'permission_id' => [ 'type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0 ],
      'created_at' => [ 'type' => 'timestamp DEFAULT current_timestamp()', 'null' => false ],
      'updated_at' => [ 'type' => 'timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()', 'null' => false ],
    ];

    $this->forge->addField($fields);
    $this->forge->addKey([ 'group_id', 'permission_id' ]);
    $this->forge->addForeignKey('group_id', 'auth_groups', 'id', '', 'CASCADE');
    $this->forge->addForeignKey('permission_id', 'auth_permissions', 'id', '', 'CASCADE');
    $this->forge->createTable('auth_groups_permissions', true);
  }

  //---------------------------------------------------------------------------
  /**
   * Rollback Tables
   */
  public function down() {
    //
    // Drop constraints first to prevent errors
    //
    if ($this->db->DBDriver != 'SQLite3') { // @phpstan-ignore-line
      $this->forge->dropForeignKey('users_options', 'users_options_user_id_foreign');
      $this->forge->dropForeignKey('auth_tokens', 'auth_tokens_user_id_foreign');
      $this->forge->dropForeignKey('auth_roles_permissions', 'auth_roles_permissions_role_id_foreign');
      $this->forge->dropForeignKey('auth_roles_permissions', 'auth_roles_permissions_permission_id_foreign');
      $this->forge->dropForeignKey('auth_roles_users', 'auth_roles_users_role_id_foreign');
      $this->forge->dropForeignKey('auth_roles_users', 'auth_roles_users_user_id_foreign');
      $this->forge->dropForeignKey('auth_users_permissions', 'auth_users_permissions_user_id_foreign');
      $this->forge->dropForeignKey('auth_users_permissions', 'auth_users_permissions_permission_id_foreign');
      $this->forge->dropForeignKey('auth_groups_users', 'auth_groups_users_group_id_foreign');
      $this->forge->dropForeignKey('auth_groups_users', 'auth_groups_users_user_id_foreign');
      $this->forge->dropForeignKey('auth_groups_permissions', 'auth_groups_permissions_group_id_foreign');
      $this->forge->dropForeignKey('auth_groups_permissions', 'auth_groups_permissions_permission_id_foreign');
    }

    //
    // Drop tables
    //
    $this->forge->dropTable('users', true);
    $this->forge->dropTable('users_options', true);
    $this->forge->dropTable('auth_logins', true);
    $this->forge->dropTable('auth_tokens', true);
    $this->forge->dropTable('auth_reset_attempts', true);
    $this->forge->dropTable('auth_activation_attempts', true);
    $this->forge->dropTable('auth_roles', true);
    $this->forge->dropTable('auth_permissions', true);
    $this->forge->dropTable('auth_roles_permissions', true);
    $this->forge->dropTable('auth_roles_users', true);
    $this->forge->dropTable('auth_users_permissions', true);
    $this->forge->dropTable('auth_groups', true);
    $this->forge->dropTable('auth_groups_users', true);
    $this->forge->dropTable('auth_groups_permissions', true);
  }
}
