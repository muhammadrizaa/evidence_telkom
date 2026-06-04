<?php
namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Project;
use App\Models\User;
use App\Models\Mapping;
use App\Models\Tematik;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with(['user', 'project', 'mapping', 'tematik', 'purchaseOrder'])
            ->whereHas('project', function ($q) {
                $q->where('team_leader_id', Auth::id());
            })
            ->latest()
            ->paginate(10);
        return view('teamleader.assignment.index', compact('assignments'));
    }

    public function create()
    {
        $projects  = Project::where('team_leader_id', Auth::id())->get();
        $karyawans = User::where('role', 'karyawan')->get();
        $mappings  = Mapping::all();
        $tematiKs  = Tematik::orderBy('nama_tematik')->get();
        $po_list   = PurchaseOrder::orderBy('no_po')->get();
        return view('teamleader.assignment.create', compact('projects', 'karyawans', 'mappings', 'tematiKs', 'po_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id'    => 'required|exists:project,id',
            'user_id'       => 'required|exists:users,id',
            'mapping_id'    => 'required|exists:mapping,id',
            'tematik_id'    => 'required|exists:tematik,id',
            'po_id'         => 'required|exists:purchase_order,id',
            'tgl_penugasan' => 'required|date',
            'deadline'      => 'required|date|after_or_equal:tgl_penugasan',
            'status_tugas'  => 'required|in:aktif,selesai',
        ]);

        Assignment::create($request->only([
            'project_id', 'user_id', 'mapping_id', 'tematik_id',
            'po_id', 'tgl_penugasan', 'deadline', 'status_tugas'
        ]));

        return redirect()->route('teamleader.assignment.index')
                         ->with('success', 'Assignment berhasil dibuat!');
    }

    public function edit($id)
    {
        $assignment = Assignment::whereHas('project', function ($q) {
            $q->where('team_leader_id', Auth::id());
        })->findOrFail($id);

        $projects  = Project::where('team_leader_id', Auth::id())->get();
        $karyawans = User::where('role', 'karyawan')->get();
        $mappings  = Mapping::all();
        $tematiKs  = Tematik::orderBy('nama_tematik')->get();
        $po_list   = PurchaseOrder::orderBy('no_po')->get();

        return view('teamleader.assignment.edit', compact('assignment', 'projects', 'karyawans', 'mappings', 'tematiKs', 'po_list'));
    }

    public function update(Request $request, $id)
    {
        $assignment = Assignment::whereHas('project', function ($q) {
            $q->where('team_leader_id', Auth::id());
        })->findOrFail($id);

        $request->validate([
            'project_id'    => 'required|exists:project,id',
            'user_id'       => 'required|exists:users,id',
            'mapping_id'    => 'required|exists:mapping,id',
            'tematik_id'    => 'required|exists:tematik,id',
            'po_id'         => 'required|exists:purchase_order,id',
            'tgl_penugasan' => 'required|date',
            'deadline'      => 'required|date|after_or_equal:tgl_penugasan',
            'status_tugas'  => 'required|in:aktif,selesai',
        ]);

        $assignment->update($request->only([
            'project_id', 'user_id', 'mapping_id', 'tematik_id',
            'po_id', 'tgl_penugasan', 'deadline', 'status_tugas'
        ]));

        return redirect()->route('teamleader.assignment.index')
                         ->with('success', 'Assignment berhasil diupdate!');
    }

    public function destroy($id)
    {
        $assignment = Assignment::whereHas('project', function ($q) {
            $q->where('team_leader_id', Auth::id());
        })->findOrFail($id);
        $assignment->delete();
        return redirect()->route('teamleader.assignment.index')
                         ->with('success', 'Assignment berhasil dihapus!');
    }
}