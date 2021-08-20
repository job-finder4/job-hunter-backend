<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project = Project::orderBy('created_at','desc')->first();
        $tasks = Task::factory()->count(5)->create(['project_id' => $project->id]);

        $projectUsers = $project->users()->get();

        foreach($tasks->take(3) as $task) {
            $projectUsers->shuffle()->first()->tasks()->save($task);
        }

    }
}
