<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Project;
// use App\Models\Assignment; ← HAPUS BARIS INI
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProject = Project::where('team_leader_id', Auth::id())->count();

        $projectAktif = Project::where('team_leader_id', Auth::id())
            ->where('status', 'aktif')->count();

        $projectSelesai = Project::where('team_leader_id', Auth::id())
            ->where('status', 'selesai')->count();

        $projectPending = Project::where('team_leader_id', Auth::id())
            ->where('status', 'pending')->count();

        $recentProjects = Project::with(['admin', 'waspang'])
            ->where('team_leader_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('teamleader.dashboard', compact(
            'totalProject',
            'projectAktif',
            'projectSelesai',
            'projectPending',
            'recentProjects'
        ));
    }
}