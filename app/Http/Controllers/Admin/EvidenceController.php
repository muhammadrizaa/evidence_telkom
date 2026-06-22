<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use App\Models\Assignment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    public function index()
    {
        $evidences = Evidence::with([
            'user',
            'purchaseOrder',
            'tematik',
            'waspang',
            'assignment.project'
        ])->latest()->paginate(10);
        return view('admin.evidence.index', compact('evidences'));
    }

    public function approve(Evidence $evidence)
    {
        $evidence->update([
            'status_laporan' => 'approved',
            'catatan_admin'  => null,
        ]);

        if ($evidence->assignment_id) {
            Assignment::where('id', $evidence->assignment_id)
                      ->update(['status_tugas' => 'selesai']);

            $assignment = Assignment::find($evidence->assignment_id);
            if ($assignment && $assignment->project_id) {
                $totalAssignment   = Assignment::where('project_id', $assignment->project_id)->count();
                $selesaiAssignment = Assignment::where('project_id', $assignment->project_id)
                                                ->where('status_tugas', 'selesai')->count();

                if ($totalAssignment > 0 && $totalAssignment === $selesaiAssignment) {
                    Project::where('id', $assignment->project_id)
                           ->update(['status' => 'selesai']);
                }
            }
        }

        return back()->with('success', 'Evidence berhasil disetujui dan assignment ditandai selesai.');
    }

    public function reject(Request $request, Evidence $evidence)
    {
        $request->validate(['catatan_admin' => 'required|string|max:255']);
        $evidence->update([
            'status_laporan' => 'rejected',
            'catatan_admin'  => $request->catatan_admin,
        ]);
        return back()->with('success', 'Evidence berhasil ditolak.');
    }

    public function destroy(Evidence $evidence)
    {
        $raw = $evidence->file_path;
        if (is_string($raw)) {
            $files = json_decode($raw, true) ?? [];
            if (is_string($files)) {
                $files = json_decode($files, true) ?? [];
            }
        } else {
            $files = $raw ?? [];
        }
        if (is_array($files)) {
            foreach ($files as $fileData) {
                $path = is_array($fileData) ? ($fileData['path'] ?? null) : $fileData;
                if ($path) Storage::disk('public')->delete($path);
            }
        }
        $evidence->delete();
        return back()->with('success', 'Evidence berhasil dihapus permanen.');
    }
}