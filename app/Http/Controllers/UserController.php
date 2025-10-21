<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Tampilkan semua user untuk monitoring (hanya role 'user' yang pernah diberi tugas)
     */
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'created_at');
        $search = $request->get('search');
        
        // Ambil hanya user dengan role 'user' yang pernah di-assign ke task
        $query = User::where('role', 'user')
            ->whereHas('assignedTasks')
            ->withCount('assignedTasks');

        // Tambahkan filter search jika ada
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Terapkan sorting berdasarkan parameter
        switch ($sortBy) {
            case 'most_diligent':
                // Paling rajin: banyak selesai, sedikit telat
                $users = $this->getUsersWithDiligenceScore($query, 'desc');
                break;
            
            case 'least_diligent':
                // Paling tidak rajin: sedikit selesai, banyak telat
                $users = $this->getUsersWithDiligenceScore($query, 'asc');
                break;
            
            case 'most_completed':
                // Paling banyak menyelesaikan tugas
                $users = $this->getUsersWithCompletedCount($query, 'desc');
                break;
            
            case 'most_late':
                // Paling sering telat
                $users = $this->getUsersWithLateCount($query, 'desc');
                break;
            
            case 'least_late':
                // Paling jarang telat
                $users = $this->getUsersWithLateCount($query, 'asc');
                break;
            
            case 'name':
                $users = $query->orderBy('name', 'asc')->paginate(15);
                break;
            
            case 'email':
                $users = $query->orderBy('email', 'asc')->paginate(15);
                break;
            
            case 'created_at':
            default:
                $users = $query->orderBy('created_at', 'desc')->paginate(15);
                break;
        }

        // Append parameters ke pagination links
        $users->appends([
            'sort_by' => $sortBy,
            'search' => $search
        ]);

        // Hitung statistik kerajinan untuk setiap user dengan eager loading
        $userIds = $users->pluck('id');
        
        // Ambil semua data submissions sekaligus
        $submissionsData = DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->whereIn('user_task_submissions.user_id', $userIds)
            ->select(
                'user_task_submissions.user_id',
                'user_task_submissions.id',
                'user_task_submissions.created_at as submission_date',
                'tasks.due_date'
            )
            ->get()
            ->groupBy('user_id');

        $users->getCollection()->transform(function ($user) use ($submissionsData) {
            $userSubmissions = $submissionsData->get($user->id, collect());
            
            $onTimeCount = $userSubmissions->filter(function($sub) {
                return $sub->submission_date <= $sub->due_date;
            })->count();
            
            $lateCount = $userSubmissions->filter(function($sub) {
                return $sub->submission_date > $sub->due_date;
            })->count();
            
            $user->diligence_score = max(0, ($onTimeCount * 10) - ($lateCount * 5));
            $user->late_submissions_count = $lateCount;
            $user->on_time_submissions_count = $onTimeCount;
            $user->completion_rate = $this->calculateCompletionRate($user);
            
            return $user;
        });

        return view('admin.users.index', compact('users', 'sortBy', 'search'));
    }

    /**
     * Get users with diligence score and sort
     */
    private function getUsersWithDiligenceScore($query, $direction = 'desc')
    {
        // Get base users
        $users = $query->get();
        
        // Calculate diligence score for each user
        $usersWithScore = $users->map(function($user) {
            $onTimeCount = $this->countOnTimeSubmissions($user);
            $lateCount = $this->countLateSubmissions($user);
            $user->temp_diligence_score = ($onTimeCount * 10) - ($lateCount * 5);
            return $user;
        });
        
        // Sort by diligence score
        $sorted = $direction === 'desc' 
            ? $usersWithScore->sortByDesc('temp_diligence_score')
            : $usersWithScore->sortBy('temp_diligence_score');
        
        // Paginate manually
        return $this->paginateCollection($sorted, 15);
    }

    /**
     * Get users with completed count and sort
     */
    private function getUsersWithCompletedCount($query, $direction = 'desc')
    {
        $users = $query->get();
        
        $usersWithCount = $users->map(function($user) {
            $user->temp_completed_count = $user->submissions()->count();
            return $user;
        });
        
        $sorted = $direction === 'desc'
            ? $usersWithCount->sortByDesc('temp_completed_count')
            : $usersWithCount->sortBy('temp_completed_count');
        
        return $this->paginateCollection($sorted, 15);
    }

    /**
     * Get users with late count and sort
     */
    private function getUsersWithLateCount($query, $direction = 'desc')
    {
        $users = $query->get();
        
        $usersWithCount = $users->map(function($user) {
            $user->temp_late_count = $this->countLateSubmissions($user);
            return $user;
        });
        
        $sorted = $direction === 'desc'
            ? $usersWithCount->sortByDesc('temp_late_count')
            : $usersWithCount->sortBy('temp_late_count');
        
        return $this->paginateCollection($sorted, 15);
    }

    /**
     * Paginate a collection manually
     */
    private function paginateCollection($collection, $perPage = 15)
    {
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;
        
        $items = $collection->slice($offset, $perPage)->values();
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }



    /**
     * Count late submissions for a user
     */
    private function countLateSubmissions($user)
    {
        return DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->where('user_task_submissions.user_id', $user->id)
            ->whereRaw('user_task_submissions.created_at > tasks.due_date')
            ->count();
    }

    /**
     * Count on-time submissions for a user
     */
    private function countOnTimeSubmissions($user)
    {
        return DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->where('user_task_submissions.user_id', $user->id)
            ->whereRaw('user_task_submissions.created_at <= tasks.due_date')
            ->count();
    }

    /**
     * Calculate completion rate percentage
     */
    private function calculateCompletionRate($user)
    {
        $totalAssigned = $user->assignedTasks()->count();
        if ($totalAssigned === 0) {
            return 0;
        }
        
        $completed = $user->submissions()->distinct('task_id')->count();
        return round(($completed / $totalAssigned) * 100, 1);
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        if ($user->role === 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Tidak diizinkan menghapus admin dari halaman ini.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Tampilkan detail user
     */
    public function show(User $user)
    {
        if ($user->role === 'admin' && auth()->user()->role !== 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Akses ditolak.');
        }

        $tasks = $user->assignedTasks()
            ->with([
                'workspace',
                'submissions' => function($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'assignedUsers',
                'creator'
            ])
            ->latest()
            ->get();

        $totalTasks = $user->total_assigned_tasks;
        $completedTasks = $user->completed_tasks_count;
        $pendingTasks = $user->pending_tasks_count;
        $todoTasks = $user->todo_tasks_count;

        // Tambahan statistik kerajinan
        $diligenceScore = $this->calculateDiligenceScore($user);
        $lateSubmissions = $this->countLateSubmissions($user);
        $onTimeSubmissions = $this->countOnTimeSubmissions($user);
        $completionRate = $this->calculateCompletionRate($user);

        return view('admin.users.show', compact(
            'user', 
            'tasks', 
            'totalTasks', 
            'completedTasks', 
            'pendingTasks', 
            'todoTasks',
            'diligenceScore',
            'lateSubmissions',
            'onTimeSubmissions',
            'completionRate'
        ));
    }
}