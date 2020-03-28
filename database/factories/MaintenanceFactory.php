<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\Maintenance;
use Faker\Generator as Faker;

$factory->define(Maintenance::class, function (Faker $faker) {
    return [
        'machine_id' => $faker->numberBetween(1, 40),
        'review_type_id' => $faker->numberBetween(1, 2),
        'description' => $faker->text,
        'review_at' => $faker->date('Y-m-d'),
    ];
});
