<?php
namespace App\Repositories\Interfaces;

interface TaskRepositoryInterface
{
    public function getAllTasks();
    public function getTaskById(int $id);
    public function storeTask(array $taskData);
    public function updateTask(int $id, array $newData);
    public function destroyTask(int $id);
    public function logTime(int $id, array $time);
    public function getTotalTime(int $id);
}
