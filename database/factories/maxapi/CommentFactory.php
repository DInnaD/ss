<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'comment' => $faker->text($maxNbChars = 200),
        'rating' => $faker->numberBetween(1, 5),
        'price' => $faker->numberBetween(1, 100),
        'user_id' => $faker->numberBetween(2, 50),
        'product_id' => $faker->numberBetween(1, 100),
    ];
});
