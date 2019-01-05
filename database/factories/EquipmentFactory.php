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

$factory->define(App\Equipment::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'serial' => str_random(32),
        'model' => str_random(32),
        'bar_code' => $faker->ean13,
//        'type_id' => $faker->text,
        'account_id' => $faker->numberBetween(1,10),
        'status_id' => function($item){
           $statuses =  \App\EquipmentStatus::where('account_id', $item['account_id'])->inRandomOrder()->get();
           if($statuses){
               return $statuses[0]->id;
           }
           return \App\EquipmentStatus::where('account_id', $item['account_id'])->inRandomOrder()->get()[0]->id;

        },
        'purchased_at' => $faker->dateTimeBetween('-10 years', '-10 days'),
        'last_service_at' => $faker->dateTimeBetween('-10 years', '-10 days'),
        'next_service_at' => $faker->dateTimeBetween('-10 years', '+3 years'),
        'insurance_valid_until' => $faker->dateTimeBetween('-10 years', '+10 years'),
        'registration_renewal_at' => $faker->dateTimeBetween('-10 years', '+10 years'),
    ];
});
