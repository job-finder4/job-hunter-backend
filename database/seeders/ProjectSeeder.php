<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manager = User::first();
        $project = Project::factory()->create(['manager_id' => $manager->id]);

        $projectUsers = User::where('id', '!=', $manager->id)
            ->get()
            ->pluck('id')
            ->shuffle()
            ->take(3);

        $project->users()->attach($projectUsers);
    }
}
