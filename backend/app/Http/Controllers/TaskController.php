<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 

class TaskController extends Controller
{
   public function index(Request $request)
    {
        $date = $request->query('date');      
        $priority = $request->query('priority');

        if (!$date) {
            return response()->json(['error' => 'date query param is required'], 400);
        }

        $query = Task::whereDate('due_date', $date);

        if ($priority && $priority !== 'all') {
            $query->where('priority', $priority);
        }

        $tasks = $query->get();
        return response()->json($tasks);
    }

    public function store(Request $request) {
    $request->validate([
        'description' => 'required|string|max:255',
        'completed' => 'boolean',
        'priority' => 'in:low,medium,high',
        'due_date' => 'date'
        
    ]);

    return Task::create($request->only(['description','completed','due_date','priority']));
    }

    public function update(Request $request, Task $task) {
        $task->update($request->only(['description','completed','due_date','priority']));
        return $task;
    }


    public function destroy(Task $task) {
        $task->delete();
        return response()->noContent();
    }

    public function sendToMake(Request $request)
    {
        
        $request->validate([
            'task_id' => 'required|integer',
            'description' => 'required|string',
            'date' => 'required|string',
        ]);


        $payload = [
            'task_id' => $request->task_id,
            'description' => $request->description,
            'date' => $request->date,
        ];

        try {
          
            $response = Http::withoutVerifying()->post(
                'https://hook.us2.make.com/962jq6g872d9el1e1opl41d5r5oxc82n',
                $payload
            );

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Task sent to Make.com',
                    'data' => $response->body(), 
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send task',
                'details' => $response->body(),
            ], 500);
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => 'Exception while sending task',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}