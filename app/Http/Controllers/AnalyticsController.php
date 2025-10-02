<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    // === Analytics untuk user ===
    public function index()
    {
        return view('analytics.index');
    }

    public function data()
    {
        $userId = Auth::id();

        // Statistik Task user
        $tasks = Task::where('user_id', $userId)
            ->selectRaw("status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        // Statistik Project user
        $projects = Project::where('user_id', $userId)->get();
        $activeProjects = $projects->where('end_date', '>=', now())->count();
        $finishedProjects = $projects->where('end_date', '<', now())->count();

        return response()->json([
            'tasks' => $tasks,
            'projects' => [
                'active' => $activeProjects,
                'finished' => $finishedProjects,
            ],
        ]);
    }

    // === Analytics Global untuk Admin ===
    public function adminIndex()
    {
        return view('admin.analyticts.index'); // tampilan admin
    }

    public function adminData()
    {
        // Statistik Task semua user
        $tasks = Task::selectRaw("status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        // Statistik Project semua user
        $projects = Project::all();
        $activeProjects = $projects->where('end_date', '>=', now())->count();
        $finishedProjects = $projects->where('end_date', '<', now())->count();

        return response()->json([
            'tasks' => $tasks,
            'projects' => [
                'active' => $activeProjects,
                'finished' => $finishedProjects,
            ],
        ]);
    }
}
