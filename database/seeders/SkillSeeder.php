<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Skill::insert([
            ['name' => 'Dev'],
            ['name' => 'Design'],
            ['name' => 'Testing'],
            ['name' => 'Bugfixing'],
        ]);
    }
}
