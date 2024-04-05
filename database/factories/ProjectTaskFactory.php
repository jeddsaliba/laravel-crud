<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProjectTask;
use Faker\Generator as Faker;

$factory->define(ProjectTask::class, function (Faker $faker) {
    return [
        'project_id' => $faker->numberBetween(1, 15),
        'created_by' => $faker->numberBetween(1, 15),
        'assigned_to' => $faker->numberBetween(1, 15),
        'name' => $faker->sentence(3),
        'description' => $faker->paragraph(5),
        'status' => $faker->randomElement(['Pending', 'Ongoing', 'Completed']),
        'created_at' => $faker->dateTimeThisYear(),
        'updated_at' => $faker->dateTimeThisYear('+12 months'),
        'start_date' => $faker->dateTimeThisYear('+12 months'),
        'end_date' => $faker->dateTimeThisYear('+12 months')
    ];
});
