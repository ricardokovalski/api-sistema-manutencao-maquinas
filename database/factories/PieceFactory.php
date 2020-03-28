<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\Peace;
use Faker\Generator as Faker;

$factory->define(Peace::class, function (Faker $faker) {
    return [
        'code' => $faker->currencyCode.$faker->randomNumber(5),
        'name' => $faker->word,
        'description' => $faker->text,
        'stock_quantity' => $faker->numberBetween(1, 20),
        'minimal_quantity' => $faker->numberBetween(1, 5),
    ];
});
