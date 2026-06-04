<?php
namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use App\Models\Waspang;
use App\Models\PurchaseOrder;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    public function index()
    {
        $assignmentIds = Assignment::where('user_id', Auth::id())->pluck('id');
        $evidences = Evidence::with(['assignment.project', 'purchaseOrder', 'tematik', 'waspang'])
                            ->whereIn('assignment_id', $assignmentIds)
                            ->latest()
                            ->paginate(10);
        return view('karyawan.evidence.index', compact('evidences'));
    }

    public function create(Request $request)
    {
        $donePoIds = Evidence::where('status_laporan', 'approved')
                             ->pluck('po_id')->unique()
                             ->map(fn($id) => (int) $id)->toArray();

        $po_list         = PurchaseOrder::whereNotIn('id', $donePoIds)->orderBy('no_po', 'asc')->get();
        $waspang_list    = Waspang::orderBy('nama_waspang', 'asc')->get();
        $assignment_list = Assignment::with(['project.tematik', 'mapping', 'purchaseOrder'])
                                     ->where('user_id', Auth::id())
                                     ->where('status_tugas', 'aktif')
                                     ->get();

        $selectedAssignment = null;
        if ($request->has('assignment_id')) {
            $selectedAssignment = Assignment::with(['project.tematik', 'project.waspang', 'purchaseOrder'])
                                            ->where('user_id', Auth::id())
                                            ->find($request->assignment_id);
        }

        return view('karyawan.evidence.create', compact(
            'waspang_list', 'po_list', 'assignment_list', 'selectedAssignment'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assignment_id' => ['required', 'integer', 'exists:assignments,id'],
            'deskripsi'     => ['nullable', 'string'],
            'file'          => ['required', 'array', 'min:1'],
            'file.*'        => ['image', 'mimes:jpeg,jpg,png'],
            'caption'       => ['nullable', 'array'],
            'caption.*'     => ['nullable', 'string', 'max:255'],
            'waspang_id'    => ['required', 'integer', 'exists:waspang,id'],
            'tematik_id'    => ['required', 'integer', 'exists:tematik,id'],
            'po_id'         => ['required', 'integer', 'exists:purchase_order,id'],
        ]);

        try {
            $fileData = [];
            $captions = $request->input('caption', []);

            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $index => $file) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->storeAs('evidences/' . Auth::id(), $originalName, 'public');
                    $fileData[] = [
                        'path'    => $path,
                        'caption' => $captions[$index] ?? $originalName,
                    ];
                }
            }

            Evidence::create([
                'assignment_id'  => $request->assignment_id,
                'po_id'          => $request->po_id,
                'waspang_id'     => $request->waspang_id,
                'tematik_id'     => $request->tematik_id,
                'deskripsi'      => $request->deskripsi,
                'file_path'      => $fileData,
                'status_laporan' => 'pending',
            ]);

            return response()->json([
                'success'     => true,
                'message'     => 'Evidence berhasil di-upload!',
                'total_files' => count($fileData),
                'redirect'    => route('karyawan.evidence.index'),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data.',
                'errors'  => ['system' => $e->getMessage()],
            ], 500);
        }
    }

    public function edit(Evidence $evidence)
    {
        $waspang_list    = Waspang::orderBy('nama_waspang', 'asc')->get();
        $po_list         = PurchaseOrder::orderBy('no_po', 'asc')->get();
        $assignment_list = Assignment::with(['project.tematik'])
                                     ->where('user_id', Auth::id())
                                     ->get();

        return view('karyawan.evidence.edit', compact('evidence', 'waspang_list', 'po_list', 'assignment_list'));
    }

    public function update(Request $request, Evidence $evidence)
    {
        $request->validate([
            'assignment_id'  => ['required', 'integer', 'exists:assignments,id'],
            'deskripsi'      => ['nullable', 'string'],
            'file'           => ['nullable', 'array'],
            'file.*'         => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'deleted_files'  => ['nullable', 'string'],
            'waspang_id'     => ['required', 'integer', 'exists:waspang,id'],
            'tematik_id'     => ['required', 'integer', 'exists:tematik,id'],
            'po_id'          => ['required', 'integer', 'exists:purchase_order,id'],
        ]);

        try {
            $raw = $evidence->file_path;
            if (is_string($raw)) {
                $fileData = json_decode($raw, true) ?? [];
                if (is_string($fileData)) {
                    $fileData = json_decode($fileData, true) ?? [];
                }
            } else {
                $fileData = $raw ?? [];
            }

            if ($request->filled('deleted_files')) {
                $deletedIndexes = json_decode($request->deleted_files, true);
                if (is_array($deletedIndexes)) {
                    foreach ($deletedIndexes as $index) {
                        if (isset($fileData[$index]['path'])) {
                            Storage::disk('public')->delete($fileData[$index]['path']);
                        }
                        unset($fileData[$index]);
                    }
                    $fileData = array_values($fileData);
                }
            }

            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->storeAs('evidences/' . Auth::id(), $originalName, 'public');
                    $fileData[] = ['path' => $path, 'caption' => $originalName];
                }
            }

            $evidence->update([
                'assignment_id'  => $request->assignment_id,
                'deskripsi'      => $request->deskripsi,
                'file_path'      => $fileData,
                'status_laporan' => 'pending',
                'waspang_id'     => $request->waspang_id,
                'tematik_id'     => $request->tematik_id,
                'po_id'          => $request->po_id,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Evidence berhasil diperbarui!',
                    'redirect' => route('karyawan.evidence.index'),
                ], 200);
            }

            return redirect()->route('karyawan.evidence.index')->with('success', 'Evidence berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
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
            foreach ($files as $file) {
                if (isset($file['path'])) {
                    Storage::disk('public')->delete($file['path']);
                }
            }
        }

        $evidence->delete();
        return redirect()->route('karyawan.evidence.index')->with('success', 'Evidence berhasil dihapus.');
    }
}