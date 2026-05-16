<?php
namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Mapping;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MappingController extends Controller
{
    public function index()
    {
        $mappings = Mapping::latest()->paginate(10);
        return view('teamleader.mapping.index', compact('mappings'));
    }

    public function create()
    {
        return view('teamleader.mapping.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_area'    => 'required|string|max:255',
            'kode_mapping' => 'required|string|max:50|unique:mapping,kode_mapping',
        ]);

        Mapping::create([
            'nama_area'    => $request->nama_area,
            'kode_mapping' => $request->kode_mapping,
        ]);

        return redirect()->route('teamleader.mapping.index')
                         ->with('success', 'Mapping berhasil ditambahkan.');
    }

    public function edit(Mapping $mapping)
    {
        return view('teamleader.mapping.edit', compact('mapping'));
    }

    public function update(Request $request, Mapping $mapping)
    {
        $request->validate([
            'nama_area'    => 'required|string|max:255',
            'kode_mapping' => ['required', 'string', 'max:50', Rule::unique('mapping', 'kode_mapping')->ignore($mapping->id)],
        ]);

        $mapping->update([
            'nama_area'    => $request->nama_area,
            'kode_mapping' => $request->kode_mapping,
        ]);

        return redirect()->route('teamleader.mapping.index')
                         ->with('success', 'Mapping berhasil diperbarui.');
    }

    public function destroy(Mapping $mapping)
    {
        $mapping->delete();
        return redirect()->route('teamleader.mapping.index')
                         ->with('success', 'Mapping berhasil dihapus.');
    }
}