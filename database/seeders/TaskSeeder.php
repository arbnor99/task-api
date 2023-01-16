<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parent_tasks = [];
        $users_ids = User::pluck('id')->toArray();

        for($i = 0; $i < 3; $i++) {
            $parent_tasks[] = [
                'user_id' => fake()->randomElement($users_ids),
                'name' => fake()->text(20),
                'description' => fake()->text(100),
                'time_logged' => fake()->randomNumber(4)
            ];
        }

        foreach($parent_tasks as $task) {
            Task::create($task);
        }
    }
}
