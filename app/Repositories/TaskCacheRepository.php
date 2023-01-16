<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Contracts\Cache\Repository;

class TaskCacheRepository implements TaskRepositoryInterface
{
    private Repository $cache;
    private TaskRepositoryInterface $taskRepository;

    private const TTL = 3600;
    private const TASK_PREFIX = 'task_';
    private const TIME_LOG_PREFIX = 'time_log_';

    public function __construct(Repository $cache, TaskRepositoryInterface $taskRepository)
    {
        $this->cache = $cache;
        $this->taskRepository = $taskRepository;
    }

    public function updateCache(Task $task)
    {
        $this->cache->put(self::TASK_PREFIX . $task->id, $task, self::TTL);
    }

    public function updateTimeLogCache(int $timeInMinutes, int $id)
    {
        $this->cache->put(self::TIME_LOG_PREFIX . $id, $timeInMinutes, self::TTL);
    }

    public function getAllTasks()
    {
        return $this->cache->remember(auth()->user()->id . 'all_tasks', self::TTL, function(){
            return $this->taskRepository->getAllTasks();
        });
    }

    public function getTaskById(int $id)
    {
        return $this->cache->remember(self::TASK_PREFIX . $id, self::TTL, function() use($id){
            return $this->taskRepository->getTaskById($id);
        });
    }

    public function storeTask(array $taskData)
    {
        $this->updateCache(
            $task = $this->taskRepository->storeTask($taskData)
        );
        return $task;
    }

    public function updateTask(int $id, array $newData)
    {
        $this->updateCache(
            $task = $this->taskRepository->updateTask($id, $newData)
        );
        return $task;
    }

    public function destroyTask(int $id)
    {
        $this->taskRepository->destroyTask($id);
        $this->cache->pull(self::TASK_PREFIX . $id);
    }

    public function logTime(int $id, array $time)
    {
        $this->updateTimeLogCache(
            $timeInMinutes = $this->taskRepository->logTime($id, $time),
            $id
        );

        return $timeInMinutes;
    }

    public function getTotalTime(int $id)
    {
        return $this->cache->remember(self::TIME_LOG_PREFIX . $id, self::TTL, function() use($id){
            return $this->taskRepository->getTotalTime($id);
        });
    }
}
