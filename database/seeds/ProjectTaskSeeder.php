<?php

use Illuminate\Database\Seeder;
use App\Models\ProjectTask;
class ProjectTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ProjectTask::class, 500)->create();
    }
}
