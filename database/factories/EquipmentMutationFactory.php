<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\EquipmentMutation::class, function (Faker\Generator $faker) {
    $name = $faker->word;
    return [
        'name' => $name,
        'display_name' => $name,
        'data_type' => 'select',
        'values' => [$faker->word, $faker->word, $faker->word, $faker->word, $faker->word],
        'is_nullable' => ($faker->numberBetween(0,1)) ? 1 : null,
        'is_replace' => ($faker->numberBetween(0,1)) ? 1 : null,
        'is_hidden' => ($faker->numberBetween(0,1)) ? 1 : null,
        'account_id' => 1,
    ];
});
