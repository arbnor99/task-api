<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(TaskResource::collection($this->taskRepository->getAllTasks()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskRepository->storeTask($request->only(['parent_id', 'name', 'description']));

        return response()->json([
            'message' => 'Task created successfully',
            'task' => TaskResource::make($task)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $task = $this->taskRepository->getTaskById($id);

        return response()->json([
            'task' => TaskResource::make($task)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $updatedTask = $this->taskRepository->updateTask($id, $request->only(['name', 'description']));

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => TaskResource::make($updatedTask)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $this->taskRepository->destroyTask($id);

        return response()->json([
            'message' => 'Task successfully deleted'
        ]);
    }
}
