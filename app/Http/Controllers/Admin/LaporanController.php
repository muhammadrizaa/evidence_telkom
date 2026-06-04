<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\SimpleType\Jc;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $karyawanList = User::where('role', 'karyawan')->orderBy('name')->get();

        $query = Evidence::with(['user', 'assignment.project'])
                         ->where('status_laporan', 'approved');

        $selectedMonthYear = $request->input('month_year');
        $selectedUserId    = $request->input('user_id');
        $selectedMonth     = null;
        $selectedYear      = null;

        if ($selectedMonthYear) {
            list($selectedMonth, $selectedYear) = explode('-', $selectedMonthYear);
            $query->whereMonth('updated_at', $selectedMonth)
                  ->whereYear('updated_at', $selectedYear);
        }

        if ($selectedUserId) {
            $query->whereHas('assignment', function($q) use ($selectedUserId) {
                $q->where('user_id', $selectedUserId);
            });
        }

        $approvedEvidences = $query->latest('updated_at')->get();

        $availableFilters = Evidence::select(
                DB::raw('YEAR(updated_at) as year'),
                DB::raw('MONTH(updated_at) as month')
            )
            ->where('status_laporan', 'approved')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.laporan.index', compact(
            'approvedEvidences',
            'availableFilters',
            'selectedMonth',
            'selectedYear',
            'karyawanList',
            'selectedUserId'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'evidence_ids'   => 'required|array|min:1',
            'evidence_ids.*' => 'exists:evidences,id',
            'format'         => 'required|in:word,pdf',
        ], [
            'evidence_ids.required' => 'Mohon pilih minimal satu evidence untuk digenerate.',
        ]);

        $evidenceIds = $request->input('evidence_ids');
        $evidences   = Evidence::with(['user', 'assignment.project', 'waspang', 'tematik'])
                               ->whereIn('id', $evidenceIds)
                               ->get();
        $format = $request->input('format');

        try {
            if ($format === 'word') return $this->generateWord($evidences);
            if ($format === 'pdf')  return $this->generatePdf($evidences);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat laporan: ' . $e->getMessage());
        }
    }

    private function generateWord($evidences)
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        foreach ($evidences as $evidence) {
            $filesData   = $this->normalizeFilesData($evidence->file_path);
            $lokasi      = $evidence->assignment->project->lokasi ?? '-';
            $imageChunks = array_chunk($filesData, 6);

            foreach ($imageChunks as $chunkIndex => $pageImages) {
                $section = $phpWord->addSection([
                    'marginTop'    => 1000,
                    'marginBottom' => 1000,
                    'marginLeft'   => 1000,
                    'marginRight'  => 1000,
                ]);

                // Header Logo
                $header     = $section->addHeader();
                $headerTable = $header->addTable(['alignment' => Jc::CENTER]);
                $headerTable->addRow();

                $cellLeft = $headerTable->addCell(4500);
                if (file_exists(public_path('images/logo-kiri.png'))) {
                    $cellLeft->addImage(public_path('images/logo-kiri.png'), ['width' => 120, 'height' => 50]);
                }

                $cellRight = $headerTable->addCell(4500);
                if (file_exists(public_path('images/logo-kanan.png'))) {
                    $cellRight->addImage(public_path('images/logo-kanan.png'), ['width' => 100, 'height' => 60, 'alignment' => Jc::END]);
                }

                // Title
                $section->addTextBreak(1);
                $section->addText(
                    'EVIDENCE PEKERJAAN',
                    ['bold' => true, 'size' => 14, 'underline' => 'single'],
                    ['alignment' => Jc::CENTER, 'spaceAfter' => 240]
                );

                // Info Table
                $infoTableStyle = ['borderSize' => 0, 'borderColor' => 'FFFFFF', 'cellMargin' => 0];
                $cellStyle      = ['borderSize' => 0, 'borderColor' => 'FFFFFF', 'valign' => 'top'];
                $infoTable      = $section->addTable($infoTableStyle);

                $rows = [
                    ['PROYEK',    "PENGADAAN PEKERJAAN OUTSIDE PLANT FIBER TO THE HOME (OSP - FTTH)\nTAHUN 2025 TELKOM REGIONAL IV KALIMANTAN"],
                    ['KONTRAK',   ''],
                    ['AREA',      'BANJARMASIN'],
                    ['LOKASI',    $lokasi],
                    ['PELAKSANA', 'PT. TELKOM AKSES'],
                ];

                foreach ($rows as $row) {
                    $infoTable->addRow();
                    $infoTable->addCell(1800, $cellStyle)->addText($row[0], ['bold' => true, 'size' => 11]);
                    $infoTable->addCell(200, $cellStyle)->addText(':', ['size' => 11]);
                    $cell = $infoTable->addCell(7000, $cellStyle);
                    foreach (explode("\n", $row[1]) as $line) {
                        $cell->addText($line, ['size' => 11]);
                    }
                }

                $section->addTextBreak(1);

                // Tabel Gambar - 3 kolom x 2 baris = 6 foto per halaman
                $imageRows = array_chunk($pageImages, 3);

                foreach ($imageRows as $row) {
                    $imageTable = $section->addTable([
                        'borderSize' => 6,
                        'borderColor' => '000000',
                        'cellMargin'  => 80,
                        'alignment'   => Jc::CENTER,
                    ]);

                    // Row gambar
                    $imageTable->addRow(2000);
                    foreach ($row as $fileData) {
                        $cell     = $imageTable->addCell(3000, ['valign' => 'center']);
                        $safePath = ltrim($fileData['path'], '/');
                        $fullPath = storage_path('app/public/' . $safePath);

                        if (file_exists($fullPath)) {
                            $cell->addImage($fullPath, ['width' => 150, 'height' => 150, 'alignment' => Jc::CENTER]);
                        }
                    }
                    for ($i = count($row); $i < 3; $i++) {
                        $imageTable->addCell(3000);
                    }

                    // Row caption
                    $imageTable->addRow();
                    foreach ($row as $fileData) {
                        $imageTable->addCell(3000, ['valign' => 'center'])->addText(
                            $fileData['caption'],
                            ['size' => 9],
                            ['alignment' => Jc::CENTER]
                        );
                    }
                    for ($i = count($row); $i < 3; $i++) {
                        $imageTable->addCell(3000);
                    }
                }
            }
        }

        $fileName  = 'Laporan-Evidence-' . now()->format('d-m-Y') . '.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path($fileName));

        return response()->download(storage_path($fileName))->deleteFileAfterSend(true);
    }

    private function generatePdf($evidences)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);

        $logoAksesBase64 = file_exists(public_path('images/logo-kiri.png'))
            ? 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo-kiri.png')))
            : null;

        $logoIndonesiaBase64 = file_exists(public_path('images/logo-kanan.png'))
            ? 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo-kanan.png')))
            : null;

        $processedEvidences = [];

        foreach ($evidences as $evidence) {
            $lokasi = $evidence->assignment->project->lokasi ?? '-';
            $raw    = $evidence->file_path;

            if (is_string($raw)) {
                $files = json_decode($raw, true) ?? [];
                if (is_string($files)) {
                    $files = json_decode($files, true) ?? [];
                }
            } else {
                $files = $raw ?? [];
            }

            $normalizedFiles = [];
            foreach ($files as $file) {
                $path    = is_array($file) ? ($file['path'] ?? '') : $file;
                $safePath = ltrim($path, '/');
                $fullPath = storage_path('app/public/' . $safePath);

                if (file_exists($fullPath)) {
                    $ext     = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                    $caption = pathinfo($path, PATHINFO_FILENAME);
                    $normalizedFiles[] = [
                        'base64'  => 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($fullPath)),
                        'caption' => $caption,
                    ];
                }
            }

            if (count($normalizedFiles) > 0) {
                $pages = array_chunk($normalizedFiles, 6);
                foreach ($pages as $pageIndex => $pageFiles) {
                    $processedEvidences[] = [
                        'lokasi'        => $lokasi,
                        'file_path'     => $pageFiles,
                        'is_first_page' => ($pageIndex === 0),
                    ];
                }
            } else {
                $processedEvidences[] = [
                    'lokasi'        => $lokasi,
                    'file_path'     => [],
                    'is_first_page' => true,
                ];
            }
        }

        $data = [
            'evidences'           => $processedEvidences,
            'logoAksesBase64'     => $logoAksesBase64,
            'logoIndonesiaBase64' => $logoIndonesiaBase64,
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf_template', $data)
            ->setPaper('A4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
            ]);

        $fileName = 'Laporan-Evidence-' . now()->format('d-m-Y') . '.pdf';
        return $pdf->download($fileName);
    }

    private function normalizeFilesData($raw)
    {
        if (is_string($raw)) {
            $files = json_decode($raw, true) ?? [];
            if (is_string($files)) {
                $files = json_decode($files, true) ?? [];
            }
        } else {
            $files = $raw ?? [];
        }

        $normalized = [];
        foreach ($files as $file) {
            if (is_array($file) && isset($file['path'])) {
                $caption = pathinfo($file['path'], PATHINFO_FILENAME);
                $normalized[] = [
                    'path'    => $file['path'],
                    'caption' => $caption,
                ];
            } elseif (is_string($file)) {
                $caption = pathinfo($file, PATHINFO_FILENAME);
                $normalized[] = [
                    'path'    => $file,
                    'caption' => $caption,
                ];
            }
        }
        return $normalized;
    }
}