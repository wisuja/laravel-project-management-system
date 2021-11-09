<?php

namespace Database\Seeders;

use App\Models\SkillExperience;
use Illuminate\Database\Seeder;

class SkillExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SkillExperience::insert([
            ['name' => 'Beginner', 'level' => 1 , 'min_exp' => 0],
            ['name' => 'Experienced', 'level' => 2 , 'min_exp' => 500],
            ['name' => 'Pro', 'level' => 3 , 'min_exp' => 1000],
        ]);
    }
}
