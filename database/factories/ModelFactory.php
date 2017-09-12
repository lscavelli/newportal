<?php

use Faker\Generator as Faker;

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
$factory->define(App\Models\User::class, function (Faker $faker) {
    static $password;

    return [
        'nome' => $faker->firstName,
        'cognome' => $faker->lastName,
        'indirizzo' => $faker->address,
        'email' => $faker->unique()->safeEmail,
        'data_nascita' =>$faker->date('Y-m-d'),
        'telefono' => $faker->phoneNumber,
        'status_id' => $faker->numerify('1'),
        'ultimo_accesso' => $faker->dateTimeThisYear('+1 year')->format('Y-m-d H:i:s'),
        'password' => $password ?: $password = bcrypt('secret'),
        'note' => $faker->text,
        'username' => $faker->userName,
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Organization::class, function (Faker $faker) {

    return [
        'name' => $faker->sentence,
        'user_id' => factory(App\Models\User::class)->create()->id,
        'username' => factory(App\Models\User::class)->create()->username,
    ];
});
