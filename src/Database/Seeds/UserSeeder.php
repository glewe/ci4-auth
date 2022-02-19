<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    //-------------------------------------------------------------------------
    /**
     * Seed the 'users' table.
     */
    public function run()
    {
        $records = array(
            ['email' => 'admin@mydomain.com', 'username' => 'admin', 'firstname' => 'Super', 'lastname' => 'Admin', 'displayname' => 'Admin'],
            ['email' => 'mmouse@mydomain.com', 'username' => 'mmouse', 'firstname' => 'Mickey', 'lastname' => 'Mouse', 'displayname' => 'Mickey'],
            ['email' => 'dduck@mydomain.com', 'username' => 'dduck', 'firstname' => 'Donald', 'lastname' => 'Duck', 'displayname' => 'Donald'],
            ['email' => 'blightyear@mydomain.com', 'username' => 'blightyear', 'firstname' => 'Buzz', 'lastname' => 'Lightyear', 'displayname' => 'Buzz'],
            ['email' => 'phead@mydomain.com', 'username' => 'phead', 'firstname' => 'Potatoe', 'lastname' => 'Head', 'displayname' => 'Potie'],
            ['email' => 'ccarl@mydomain.com', 'username' => 'ccarl', 'firstname' => 'Coyote', 'lastname' => 'Carl', 'displayname' => 'Carl'],
            ['email' => 'sgonzalez@mydomain.com', 'username' => 'sgonzalez', 'firstname' => 'Speedy', 'lastname' => 'Gonzalez', 'displayname' => 'Speedy'],
        );

        // Simple Queries
        // $this->db->query("INSERT INTO users (username, email) VALUES(:username:, :email:)", $data);

        // Using Query Builder
        foreach ($records as $record) {

            $this->db->table('users')->insert($record);
        }
    }
}
