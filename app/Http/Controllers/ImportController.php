<?php

namespace App\Http\Controllers;

use App\Imports\DataLabelSbsiteImport;
use App\Models\DataLabelSbsite;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        return view('import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        $file = $request->file('file_excel');

        try {
            return DB::transaction(function () use ($file) {
                $uploadVersion = $this->generateUniqueVersion();

                $import = new DataLabelSbsiteImport($uploadVersion);
                Excel::import($import, $file);

                $total = DataLabelSbsite::where('upload_version', $uploadVersion)->count();

                if ($total === 0) {
                    throw new \Exception('Tidak ada data yang berhasil di-import. Periksa format file Excel.');
                }

                return response()->json([
                    'success' => true,
                    'message' => "Import berhasil! {$total} baris data dimasukkan untuk upload_version {$uploadVersion}.",
                    'total' => $total,
                    'version' => $uploadVersion,
                ]);
            });
        } catch (QueryException $e) {
            report($e);

            $message = str_contains($e->getMessage(), 'id_sb_site') && str_contains($e->getMessage(), 'cannot be null')
                ? 'Kolom Site ID belum diisi. Lengkapi kolom Site ID pada file Excel, lalu unggah kembali.'
                : 'Data file tidak sesuai. Periksa kembali file Excel, lalu unggah kembali.';

            return response()->json([
                'success' => false,
                'message' => $message,
                'error_type' => 'validation',
            ], 500);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Periksa kembali format file Excel, lalu unggah kembali.',
                'error_type' => 'import_error',
            ], 500);
        }
    }

    private function generateUniqueVersion(): string
    {
        $datePrefix = now()->format('Ymd');
        $maxAttempts = 100;

        for ($i = 1; $i <= $maxAttempts; $i++) {
            $suffix = str_pad($i, 2, '0', STR_PAD_LEFT);
            $candidate = $datePrefix . '-' . $suffix;

            if (!DataLabelSbsite::where('upload_version', $candidate)->exists()) {
                return $candidate;
            }
        }

        return $datePrefix . '-' . str_pad(random_int(10, 99), 2, '0', STR_PAD_LEFT);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv,txt',
        ]);

        $file = $request->file('file_excel');

        try {
            $reader = Excel::toArray(new DataLabelSbsiteImport('preview'), $file);
            $data = $reader[0] ?? [];

            $rows = array_slice($data, 0, 50);

            return response()->json([
                'success' => true,
                'headers' => array_keys($data[0] ?? []),
                'rows' => $rows,
                'total_rows' => count($data),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Preview gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function versions()
    {
        $versions = DataLabelSbsite::select('upload_version')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status_received = 1 THEN 1 ELSE 0 END) as scanned')
            ->groupBy('upload_version')
            ->orderByDesc('upload_version')
            ->get()
            ->map(function ($v) {
                $v->percent = $v->total > 0 ? round(($v->scanned / $v->total) * 100) : 0;

                return $v;
            });

        return response()->json([
            'success' => true,
            'versions' => $versions,
        ]);
    }

    public function showVersion($version)
    {
        $records = DataLabelSbsite::where('upload_version', $version)
            ->orderBy('no_urut')
            ->paginate(50);

        $stats = DataLabelSbsite::where('upload_version', $version)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(qty) as total_qty')
            ->selectRaw('AVG(qty) as avg_qty')
            ->selectRaw('MIN(qty) as min_qty')
            ->selectRaw('MAX(qty) as max_qty')
            ->selectRaw('SUM(CASE WHEN status_received = 1 THEN 1 ELSE 0 END) as scanned')
            ->first();

        return view('version-detail', [
            'version' => $version,
            'records' => $records,
            'stats' => $stats,
        ]);
    }

    public function destroyVersion($version)
    {
        try {
            $deleted = DataLabelSbsite::where('upload_version', $version)->delete();

            return redirect()->route('data')
                ->with('success', "Version {$version} berhasil dihapus ({$deleted} records).");
        } catch (\Exception $e) {
            return redirect()->route('data')
                ->with('error', 'Gagal menghapus version: '.$e->getMessage());
        }
    }

    public function data()
    {
        $versions = DataLabelSbsite::select('upload_version')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(qty) as total_qty')
            ->selectRaw('AVG(qty) as avg_qty')
            ->selectRaw('MIN(qty) as min_qty')
            ->selectRaw('MAX(qty) as max_qty')
            ->selectRaw('SUM(CASE WHEN status_received = 1 THEN 1 ELSE 0 END) as scanned')
            ->groupBy('upload_version')
            ->orderByDesc('upload_version')
            ->get();

        $summary = [
            'total_versions' => $versions->count(),
            'total_records' => $versions->sum('total'),
            'total_qty' => $versions->sum('total_qty'),
            'total_scanned' => $versions->sum('scanned'),
        ];

        return view('data', [
            'versions' => $versions,
            'summary' => $summary,
        ]);
    }
}
