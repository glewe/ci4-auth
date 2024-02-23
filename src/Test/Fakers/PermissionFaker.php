<?php

namespace CI4\Auth\Test\Fakers;

use Faker\Generator;
use CI4\Auth\Authorization\PermissionModel;

class PermissionFaker extends PermissionModel {
  /**
   * Faked data for Fabricator.
   *
   * @param Generator $faker
   *
   * @return array
   */
  public function fake(Generator &$faker): array {
    return [
      'name' => $faker->word,
      'description' => $faker->sentence,
    ];
  }
}
