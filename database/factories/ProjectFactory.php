<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'created_by' => $faker->numberBetween(1, 25),
        'name' => $faker->sentence(3),
        'description' => $faker->paragraph(5)
    ];
});
