<?php

namespace App\Http\Controllers;

use App\Models\DataLabelSbsite;
use Illuminate\Http\Request;

class InboundCheckController extends Controller
{
    public function index(Request $request)
    {
        return view('inbound-check');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'upload_version' => 'required|string',
        ]);

        $barcode = trim($request->input('barcode'));
        $uploadVersion = trim($request->input('upload_version'));

        $record = DataLabelSbsite::where('id_vendor', $barcode)
            ->where('upload_version', $uploadVersion)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'found' => false,
                'message' => 'Barang Tidak Terdaftar!',
                'alarm' => 'tetot',
            ]);
        }

        if ($record->status_received) {
            return response()->json([
                'success' => false,
                'found' => true,
                'duplicate' => true,
                'message' => 'Barang Ini Sudah Pernah Di-scan Sebelumnya! (Duplikat)',
                'alarm' => 'error',
            ]);
        }

        $record->update(['status_received' => true]);

        $total = DataLabelSbsite::where('upload_version', $uploadVersion)->count();
        $scanned = DataLabelSbsite::where('upload_version', $uploadVersion)
            ->where('status_received', true)
            ->count();

        return response()->json([
            'success' => true,
            'found' => true,
            'duplicate' => false,
            'message' => 'Barang Berhasil Di-scan! Silakan Cetak Stiker.',
            'print_data' => [
                'id_sb_site' => $record->id_sb_site,
                'item' => $record->item,
                'po' => $record->po,
                'country' => $record->country,
                'building' => $record->building,
                'cell' => $record->cell,
                'sdd' => $record->sdd->format('Y-m-d'),
                'qty' => $record->qty,
            ],
            'progress' => [
                'scanned' => $scanned,
                'total' => $total,
            ],
        ]);
    }

    public function versions()
    {
        $versions = DataLabelSbsite::select('upload_version')
            ->distinct()
            ->orderBy('upload_version', 'desc')
            ->get()
            ->pluck('upload_version');

        return response()->json($versions);
    }

    public function progress(Request $request)
    {
        $request->validate([
            'upload_version' => 'required|string',
        ]);

        $uploadVersion = trim($request->input('upload_version'));

        $total = DataLabelSbsite::where('upload_version', $uploadVersion)->count();
        $scanned = DataLabelSbsite::where('upload_version', $uploadVersion)
            ->where('status_received', true)
            ->count();

        return response()->json([
            'scanned' => $scanned,
            'total' => $total,
        ]);
    }
}
