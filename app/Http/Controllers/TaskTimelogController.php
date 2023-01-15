<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogTimeRequest;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskTimelogController extends Controller
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'Time log' => $this->taskRepository->getTotalTime($id)
        ]);
    }

    public function store(LogTimeRequest $request, $id): JsonResponse
    {
        $this->taskRepository->logTime($id, $request->only(['days', 'hours', 'minutes']));

        return response()->json([
            'message' => 'Successfully logged time'
        ]);
    }
}
