<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProjectTask;
use Faker\Generator as Faker;

$factory->define(ProjectTask::class, function (Faker $faker) {
    return [
        'project_id' => $faker->numberBetween(1, 10),
        'created_by' => $faker->numberBetween(1, 10),
        'assigned_to' => $faker->numberBetween(1, 10),
        'name' => $faker->sentence(3),
        'description' => $faker->paragraph(5),
        'status' => $faker->randomElement(['Pending', 'Ongoing', 'Completed']),
        'start_date' => $faker->dateTimeBetween('-2 weeks', 'now'),
        'end_date' => $faker->dateTimeBetween('+1 week', '+3 months')
    ];
});
