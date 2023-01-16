<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $users_ids = User::pluck('id')->toArray();
        $tasks_ids = Task::pluck('id')->toArray();
        return [
            'user_id' => fake()->randomElement($users_ids),
            'parent_id' => fake()->randomElement($tasks_ids),
            'name' => fake()->text(20),
            'description' => fake()->text(100),
            'time_logged' => fake()->randomNumber(4)
        ];
    }
}
