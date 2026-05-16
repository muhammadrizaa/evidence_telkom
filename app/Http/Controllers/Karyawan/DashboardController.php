<?php
namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Statistik evidence
        $stats = Evidence::where('assignment_id', function($query) use ($userId) {
                        $query->select('id')->from('assignments')->where('user_id', $userId);
                    })
                    ->select('status_laporan', DB::raw('count(*) as total'))
                    ->groupBy('status_laporan')
                    ->pluck('total', 'status_laporan');

        $totalEvidence = $stats->sum();
        $pendingCount  = $stats->get('pending', 0);
        $approvedCount = $stats->get('approved', 0);
        $rejectedCount = $stats->get('rejected', 0);

        // Assignment aktif milik karyawan
        $assignments = Assignment::with(['project', 'mapping'])
                                 ->where('user_id', $userId)
                                 ->latest()
                                 ->get();

        // Evidence terbaru 7 hari
        $recentEvidences = Evidence::whereHas('assignment', function($query) use ($userId) {
                                $query->where('user_id', $userId);
                            })
                            ->where('created_at', '>=', Carbon::now()->subDays(7))
                            ->latest()
                            ->get();

        return view('karyawan.dashboard', [
            'totalEvidence'   => $totalEvidence,
            'pendingCount'    => $pendingCount,
            'approvedCount'   => $approvedCount,
            'rejectedCount'   => $rejectedCount,
            'recentEvidences' => $recentEvidences,
            'assignments'     => $assignments,
        ]);
    }
}