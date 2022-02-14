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
            ['email' => 'mmouse@mydomain.com', 'username' => 'mickey', 'firstname' => 'Mickey', 'lastname' => 'Mouse', 'displayname' => 'Mickey'],
            ['email' => 'dduck@disnmydomainey.com', 'username' => 'donald', 'firstname' => 'Donald', 'lastname' => 'Duck', 'displayname' => 'Donald'],
            ['email' => 'blightyear@mydomain.com', 'username' => 'buzz', 'firstname' => 'Buzz', 'lastname' => 'Lightyear', 'displayname' => 'Buzz'],
            ['email' => 'phead@mydomain.com', 'username' => 'potatoe', 'firstname' => 'Potatoe', 'lastname' => 'Head', 'displayname' => 'Potie'],
            ['email' => 'ccarl@mydomain.com', 'username' => 'carl', 'firstname' => 'Coyote', 'lastname' => 'Carl', 'displayname' => 'Carl'],
            ['email' => 'sgponzalez@mydomain.com', 'username' => 'speedy', 'firstname' => 'Speedy', 'lastname' => 'Gonzalez', 'displayname' => 'Speedy'],
        );

        // Simple Queries
        // $this->db->query("INSERT INTO users (username, email) VALUES(:username:, :email:)", $data);

        // Using Query Builder
        foreach ($records as $record) {

            $this->db->table('users')->insert($record);
        }
    }
}
