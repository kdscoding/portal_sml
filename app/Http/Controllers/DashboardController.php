<?php

namespace App\Http\Controllers;

use App\Models\DataLabelSbsite;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVersions = DataLabelSbsite::distinct('upload_version')->count('upload_version');
        $totalRecords = DataLabelSbsite::count();
        $totalScanned = DataLabelSbsite::where('status_received', true)->count();
        $totalPending = DataLabelSbsite::where('status_received', false)->count();

        $latestVersions = DataLabelSbsite::select('upload_version')
            ->distinct()
            ->orderBy('upload_version', 'desc')
            ->limit(8)
            ->pluck('upload_version');

        $versionProgress = [];
        foreach ($latestVersions as $version) {
            $total = DataLabelSbsite::where('upload_version', $version)->count();
            $scanned = DataLabelSbsite::where('upload_version', $version)
                ->where('status_received', true)
                ->count();
            $versionProgress[] = [
                'version' => $version,
                'total' => $total,
                'scanned' => $scanned,
                'percent' => $total > 0 ? round(($scanned / $total) * 100) : 0,
            ];
        }

        $recentScans = DataLabelSbsite::where('status_received', true)
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        return view('dashboard', [
            'totalVersions' => $totalVersions,
            'totalRecords' => $totalRecords,
            'totalScanned' => $totalScanned,
            'totalPending' => $totalPending,
            'versionProgress' => $versionProgress,
            'recentScans' => $recentScans,
        ]);
    }
}
