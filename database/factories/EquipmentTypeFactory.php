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

$factory->define(App\EquipmentType::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text,
//        'insurance' => $faker->numberBetween(0,1),
//        'registration' => $faker->numberBetween(0,1),
//        'service' => $faker->numberBetween(0,1),
        'account_id' => $faker->numberBetween(1,10),
    ];
});
