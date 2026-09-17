<?php

namespace App\Http\Controllers;

use App\Imports\DataLabelSbsiteImport;
use App\Models\DataLabelSbsite;
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
        $maxAttempts = 10;

        try {
            return DB::transaction(function () use ($file, $maxAttempts) {
                $datePrefix = now()->format('Ymd');

                $uploadVersion = null;
                for ($i = 0; $i < $maxAttempts; $i++) {
                    $suffix = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                    $candidate = $datePrefix . '-' . $suffix;

                    $existing = DB::table('data_label_sbsite')
                        ->where('upload_version', $candidate)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->first();

                    if (!$existing) {
                        $uploadVersion = $candidate;
                        break;
                    }
                }

                if (!$uploadVersion) {
                    $uploadVersion = $datePrefix . '-' . str_pad(mt_rand(10, 99), 2, '0', STR_PAD_LEFT);
                }

                DB::table('data_label_sbsite')->where('upload_version', $uploadVersion)->delete();

                $import = new DataLabelSbsiteImport($uploadVersion);
                Excel::import($import, $file);

                $total = DataLabelSbsite::where('upload_version', $uploadVersion)->count();

                return response()->json([
                    'success' => true,
                    'message' => "Import berhasil! {$total} baris data dimasukkan untuk upload_version {$uploadVersion}.",
                    'total' => $total,
                    'version' => $uploadVersion,
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import gagal: ' . $e->getMessage(),
            ], 500);
        }
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
}
