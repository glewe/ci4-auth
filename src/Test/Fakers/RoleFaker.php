<?php

namespace CI4\Auth\Test\Fakers;

use Faker\Generator;
use CI4\Auth\Authorization\RoleModel;
use stdClass;

class RoleFaker extends RoleModel {
  /**
   * Faked data for Fabricator.
   *
   * @param Generator $faker
   *
   * @return stdClass
   */
  public function fake(Generator &$faker): stdClass {
    return (object)[
      'name' => $faker->word,
      'description' => $faker->sentence,
    ];
  }
}
