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

$factory->define(App\EquipmentHistory::class, function (Faker\Generator $faker) {
    return [
        'lead_id' => $faker->numberBetween(1,1000000),
        'placed_at' => (rand(0,1)) ? $faker->dateTimeBetween('-10 years', 'now') : null,
        'removed_at' =>  function (array $item) {
            if($item['placed_at']){
                if(rand(0,1)){
                    $carbon = new \Carbon\Carbon($item['placed_at']->format('Y-m-d H:i:s'));
                    return $carbon->addDays(rand(1, 100))
                        ->addHours(rand(1, 23))
                        ->addMinutes(rand(1, 59))
                        ->addSeconds(rand(1, 59))
                        ->toDateTimeString();
                }
            }
            return null;
        }
        //$faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now'),
    ];
});
