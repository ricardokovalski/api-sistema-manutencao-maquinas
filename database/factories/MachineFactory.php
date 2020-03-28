<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use \App\Entities\Machine;
use Faker\Generator as Faker;

$factory->define(Machine::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'technical' => $faker->text,
        'patrimony' => $faker->currencyCode,
        'review_period' => $faker->numberBetween(1, 5),
        'warning_period' => $faker->numberBetween(1, 5),
        'warning_email_address' => $faker->email,
    ];
});
