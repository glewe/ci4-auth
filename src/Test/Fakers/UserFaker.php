<?php

namespace CI4\Auth\Test\Fakers;

use Faker\Generator;
use CI4\Auth\Entities\User;
use CI4\Auth\Models\UserModel;

class UserFaker extends UserModel {
  /**
   * Faked data for Fabricator.
   *
   * @param Generator $faker
   *
   * @return User
   */
  public function fake(Generator &$faker): User {
    return new User([
      'email' => $faker->email,
      'username' => $faker->userName,
      'password' => bin2hex(random_bytes(16)),
    ]);
  }
}
