<?php
namespace App\Repositories;

use App\Models\Task;
use App\Models\User;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Carbon\CarbonInterval;

class TaskEloquentRepository implements TaskRepositoryInterface
{
    public function getAllTasks()
    {
        return Task::where('user_id', auth()->user()->id)->paginate(10);
    }

    public function getTaskById(int $id)
    {
        $task = Task::where([
            ['user_id', auth()->user()->id],
            ['id', $id]
        ])->with('children')->firstOrFail();

        return $task;
    }

    public function storeTask(array $taskData)
    {
        return Task::create(array_merge(
            ['user_id' => auth()->user()->id],
            $taskData
        ));
    }

    public function updateTask($id, array $newData)
    {
        $task = $this->getTaskById($id);
        $task->update($newData);

        return $task;
    }

    public function destroyTask($id)
    {
        $task = $this->getTaskById($id);
        $task->delete();
    }

    public function logTime(int $id, array $time)
    {
        $timeInMinutes = $time['days'] * 1440 + $time['hours'] * 60 + $time['minutes'];
        $task = $this->getTaskById($id);
        $task->time_logged = $timeInMinutes;
        $task->save();

        return $timeInMinutes;
    }

    public function getTotalTime(int $id)
    {
        $task = $this->getTaskById($id);
        $totalTime = $task->time_logged;
        $this->calculateTotalTime($task, $totalTime);

        return CarbonInterval::minutes($totalTime)->cascade()->forHumans();
    }

    public function calculateTotalTime(Task $task, &$totalTime)
    {
        if($task->children->count() > 0) {
            foreach($task->children as $child) {
                $totalTime += $child->time_logged;
                $this->calculateTotalTime($child, $totalTime);
            }
        }
    }
}
