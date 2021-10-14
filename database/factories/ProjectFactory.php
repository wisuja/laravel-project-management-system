<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'from' => $this->faker->date('Y-m-d'),
            'to' => $this->faker->date('Y-m-d'),
            'is_starred' => false,
            'created_by' => function () {
                return User::first() ? User::first()->id : User::factory()->create()->id;
            }
        ];
    }
}
