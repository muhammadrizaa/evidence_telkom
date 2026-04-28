<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Waspang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['admin', 'waspang'])
            ->where('team_leader_id', Auth::id())
            ->latest()
            ->get();

        return view('teamleader.project.index', compact('projects'));
    }

    public function create()
    {
        $admins   = User::where('role', 'admin')->get();
        $waspangs = Waspang::all();

        return view('teamleader.project.create', compact('admins', 'waspangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'lokasi'       => 'required|string',
            'deskripsi'    => 'nullable|string',
            'admin_id'     => 'required|exists:users,id',
            'waspang_id'   => 'required|exists:waspang,id',
            'status'       => 'required|in:pending,aktif,selesai',
        ]);

        Project::create([
            'nama_project'   => $request->nama_project,
            'lokasi'         => $request->lokasi,
            'deskripsi'      => $request->deskripsi,
            'admin_id'       => $request->admin_id,
            'waspang_id'     => $request->waspang_id,
            'status'         => $request->status,
            'team_leader_id' => Auth::id(),
        ]);

        return redirect()->route('teamleader.project.index')
            ->with('success', 'Project berhasil dibuat!');
    }

    public function show($id)
    {
        $project = Project::with(['admin', 'waspang', 'assignments.user', 'assignments.mapping'])
            ->where('team_leader_id', Auth::id())
            ->findOrFail($id);

        return view('teamleader.project.show', compact('project'));
    }

    public function edit($id)
    {
        $project  = Project::where('team_leader_id', Auth::id())->findOrFail($id);
        $admins   = User::where('role', 'admin')->get();
        $waspangs = Waspang::all();

        return view('teamleader.project.edit', compact('project', 'admins', 'waspangs'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::where('team_leader_id', Auth::id())->findOrFail($id);

        $request->validate([
            'nama_project' => 'required|string|max:255',
            'lokasi'       => 'required|string',
            'deskripsi'    => 'nullable|string',
            'admin_id'     => 'required|exists:users,id',
            'waspang_id'   => 'required|exists:waspang,id',
            'status'       => 'required|in:pending,aktif,selesai',
        ]);

        $project->update([
            'nama_project' => $request->nama_project,
            'lokasi'       => $request->lokasi,
            'deskripsi'    => $request->deskripsi,
            'admin_id'     => $request->admin_id,
            'waspang_id'   => $request->waspang_id,
            'status'       => $request->status,
        ]);

        return redirect()->route('teamleader.project.index')
            ->with('success', 'Project berhasil diupdate!');
    }

    public function destroy($id)
    {
        $project = Project::where('team_leader_id', Auth::id())->findOrFail($id);
        $project->delete();

        return redirect()->route('teamleader.project.index')
            ->with('success', 'Project berhasil dihapus!');
    }
}