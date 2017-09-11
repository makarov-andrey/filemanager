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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\File::class, function (Faker\Generator $faker) {
    return [
        'code' => str_random(64),
        'name' => $faker->realText($faker->numberBetween(10,100)) . $faker->fileExtension,
        'email' => $faker->safeEmail,
        'visitor_hash' => str_random(60),
        'description' => $faker->realText($faker->numberBetween(10,1000)),
        'created_at' => ($created_at = $faker->dateTimeBetween('- 2 years', 'now')),
        'updated_at' => $faker->dateTimeBetween($created_at, 'now')
    ];
});