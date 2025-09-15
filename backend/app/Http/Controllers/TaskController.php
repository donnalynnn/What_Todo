<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

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
}