<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOwnsData
{
    public function handle(Request $request, Closure $next)
    {
        $workspace = $request->route('workspace');
        $task = $request->route('task');

        if ($workspace && $workspace->admin_id !== auth()->id()) {
            abort(403);
        }

        if ($task && $task->admin_id !== auth()->id()) {
            abort(403);
        }

        return $next($request);
    }
}
