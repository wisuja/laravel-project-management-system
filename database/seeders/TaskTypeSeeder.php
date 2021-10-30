<?php

namespace Database\Seeders;

use App\Models\TaskType;
use Illuminate\Database\Seeder;

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskType::insert([
            ['name' => 'Task'],
            ['name' => 'Story'],
            ['name' => 'Epic'],
            ['name' => 'Bug'],
        ]);
    }
}
