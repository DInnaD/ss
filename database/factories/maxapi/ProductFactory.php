<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' =>  $faker->company,
        'description' => $faker->text($maxNbChars = 200),
        'weight' => $faker->numberBetween(1, 500),
        'barcode' => $faker->ean13,
        'category_id' => $faker->numberBetween(1, 5),
        'user_id' => $faker->numberBetween(2, 50),
    ];
});
