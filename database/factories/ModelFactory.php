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

$factory->define(\App\Seed\User::class, function (Faker\Generator $faker) {

    $username = $faker->userName;

    $gender = $faker->randomElement(['male', 'female', 'other']);

    $fist_name = $gender === 'female' ? $faker->firstNameFemale : $faker->firstNameMale;

    return [
        'username' => $username,
        'email' => $faker->email,
        'is_activated' => 1,
        'first_name' => $fist_name,
        'last_name' => $faker->lastName,
        'password' => password_hash($username . '12345', PASSWORD_DEFAULT),
        'gender' => $gender,
        'sexual_orientation' => $faker->randomElement(['bisexual', 'homosexual', 'heterosexual']),
        'description' => $faker->paragraph(2),
        'score' => $faker->numberBetween(-500, 3000),
        'birthday' => $faker->dateTimeBetween('-50 years', '-18 years'),
        'lat' => $faker->numberBetween(49300, 55500) / 1000,
        'lng' => $faker->numberBetween(-1000, 5000) / 1000
    ];
});

$factory->define(\App\Seed\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});
