<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    // API endpoint untuk ambil data event (task & project)
    public function events(Request $request)
    {
        $tasks = Task::where('user_id', Auth::id())->get()->map(function ($task) {
            return [
                'id' => 'task-' . $task->id,
                'title' => '[Task] ' . $task->title,
                'start' => $task->due_date,
                'end' => $task->due_date,
                'color' => $task->status === 'done' ? 'green' : 'orange',
                'url' => route('tasks.show', $task), // klik diarahkan ke edit task
            ];
        });

        $projects = Project::where('user_id', Auth::id())->get()->map(function ($project) {
            return [
                'id' => 'project-' . $project->id,
                'title' => '[Project] ' . $project->name,
                'start' => $project->start_date,
                'end' => $project->end_date,
                'color' => 'blue',
                'url' => route('projects.show', $project), // klik diarahkan ke detail project
            ];
        });

        return response()->json($tasks->merge($projects));
    }
}
