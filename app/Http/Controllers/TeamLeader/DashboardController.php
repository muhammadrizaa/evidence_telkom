<?php
namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Stats project
        $totalProject   = Project::where('team_leader_id', $userId)->count();
        $projectAktif   = Project::where('team_leader_id', $userId)->where('status', 'aktif')->count();
        $projectPending = Project::where('team_leader_id', $userId)->where('status', 'pending')->count();
        $projectSelesai = Project::where('team_leader_id', $userId)->where('status', 'selesai')->count();

        // Stats assignment
        $totalAssignment = Assignment::whereHas('project', function($q) use ($userId) {
            $q->where('team_leader_id', $userId);
        })->count();

        $deadlineDekat = Assignment::whereHas('project', function($q) use ($userId) {
            $q->where('team_leader_id', $userId);
        })
        ->where('status_tugas', 'aktif')
        ->whereNotNull('deadline')
        ->whereDate('deadline', '<=', Carbon::now()->addDays(7))
        ->count();

        // Recent projects
        $recentProjects = Project::with(['admin', 'waspang', 'tematik'])
            ->where('team_leader_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Assignment deadline dekat
        $nearDeadlineAssignments = Assignment::with(['user', 'project', 'purchaseOrder'])
            ->whereHas('project', function($q) use ($userId) {
                $q->where('team_leader_id', $userId);
            })
            ->where('status_tugas', 'aktif')
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<=', Carbon::now()->addDays(7))
            ->orderBy('deadline', 'asc')
            ->take(5)
            ->get();

        return view('teamleader.dashboard', compact(
            'totalProject', 'projectAktif', 'projectPending', 'projectSelesai',
            'totalAssignment', 'deadlineDekat',
            'recentProjects', 'nearDeadlineAssignments'
        ));
    }
}