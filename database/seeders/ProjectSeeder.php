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
        $projects = Project::factory(10)->create();

        foreach ($projects as $project) {
            $user = User::factory()->create();
            $project->members()->attach($user->id, [
                'lead' => $user->id
            ]);
        }
    }
}
