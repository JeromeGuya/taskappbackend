<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    //

    public function index()
    {
        $tasks = Task::get();
        if($tasks->count() > 0) {
            return TaskResource::collection($tasks);
        }else {
            return response()->json(['message' => 'No tasks found'], 200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'required|string',
            'task_description' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json(['message' => 'All fields are required', 'error' => $validator->messages()], 422);
        }

        $task = Task::create([
            'task_name' => $request->task_name,
            'task_description' => $request->task_description,
            'task_status' => 'pending'
        ]);

        return response()->json(['message' => 'Task created successfully', 'data' => new TaskResource($task)], 200);

    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'required|string',
            'task_description' => 'required|string',
            'task_status' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json(['message' => 'All fields are required', 'error' => $validator->messages()], 422);
        }

        $task->update([
            'task_name' => $request->task_name,
            'task_description' => $request->task_description,
            'task_status' => $request->task_status
        ]);

        return response()->json(['message' => 'Task updated successfully', 'data' => new TaskResource($task)], 200);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
