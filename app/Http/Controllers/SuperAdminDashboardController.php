<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Exports\BurialPermitExport;
use Maatwebsite\Excel\Facades\Excel;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $year = now()->year;

        $totalPermits = BurialPermit::count();
        $activePermits = BurialPermit::where('status', 'active')->count();
        $expiringPermits = BurialPermit::where('status', 'expiring')->count();
        $expiredPermits = BurialPermit::where('status', 'expired')->count();
        $permitsThisMonth = BurialPermit::whereMonth('created_at', now()->month)
            ->whereYear('created_at', $year)->count();
        $totalDeceased = DeceasedPerson::count();
        $deceasedThisMonth = DeceasedPerson::whereMonth('created_at', now()->month)
            ->whereYear('created_at', $year)->count();

        $newPermits = BurialPermit::whereYear('created_at', $year)->count();
        $renewedPermits = BurialPermit::whereYear('updated_at', $year)
            ->whereYear('created_at', '<', $year)
            ->where('status', 'active')
            ->count();

        // Monthly bar chart (Jan–Dec)
        $monthly = BurialPermit::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, COUNT(*) as total")
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = $monthly[$m] ?? 0;
        }

        // Fee type breakdown
        $feeCounts = BurialPermit::selectRaw('permit_type, COUNT(*) as total')
            ->groupBy('permit_type')
            ->pluck('total', 'permit_type')
            ->toArray();

        $feeTypeData = [
            $feeCounts['cemented'] ?? 0,
            $feeCounts['niche_1st'] ?? 0,
            $feeCounts['niche_2nd'] ?? 0,
            $feeCounts['niche_3rd'] ?? 0,
            $feeCounts['niche_4th'] ?? 0,
            $feeCounts['bone_niches'] ?? 0,
            collect($feeCounts)->except(['cemented', 'niche_1st', 'niche_2nd', 'niche_3rd', 'niche_4th', 'bone_niches'])->sum(),
        ];

        $recentPermits = BurialPermit::with('deceased')->latest()->limit(8)->get();

        // Recent activity for the dashboard preview (real logs)
        $recentActivity = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('superadmin.dashboard', compact(
            'totalPermits',
            'activePermits',
            'expiringPermits',
            'expiredPermits',
            'permitsThisMonth',
            'totalDeceased',
            'deceasedThisMonth',
            'monthlyData',
            'feeTypeData',
            'recentPermits',
            'recentActivity',
            'newPermits',
            'renewedPermits'
        ));
    }

    public function export()
    {
        $year = now()->year;

        $totalPermits    = BurialPermit::count();
        $activePermits   = BurialPermit::where('status', 'active')->count();
        $expiringPermits = BurialPermit::where('status', 'expiring')->count();
        $expiredPermits  = BurialPermit::where('status', 'expired')->count();
        $totalDeceased   = DeceasedPerson::count();

        $newPermits = BurialPermit::whereYear('created_at', $year)->count();
        $renewedPermits = BurialPermit::whereYear('updated_at', $year)
            ->whereYear('created_at', '<', $year)
            ->where('status', 'active')
            ->count();

        $monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];

        $monthly = BurialPermit::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, COUNT(*) as total")
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // 0-indexed (Jan=0…Dec=11) to match the blade @foreach
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m - 1] = $monthly[$m] ?? 0;
        }

        $maxVal       = max(array_merge($monthlyData, [1]));
        $peakIdx      = array_search($maxVal, $monthlyData);
        $busiestMonth = $monthNames[$peakIdx] ?? '—';
        $busiestCount = $maxVal;

        $feeCounts = BurialPermit::selectRaw('permit_type, COUNT(*) as total')
            ->groupBy('permit_type')
            ->pluck('total', 'permit_type')
            ->toArray();

        $recentPermits = BurialPermit::with('deceased')->latest()->limit(15)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('superadmin.pdf_export', compact(
            'totalPermits', 'activePermits', 'expiringPermits',
            'expiredPermits', 'totalDeceased',
            'newPermits', 'renewedPermits',
            'monthlyData', 'busiestMonth', 'busiestCount',
            'feeCounts', 'recentPermits', 'year'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('LGU-Carmen-Burial-Report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new BurialPermitExport, 'LGU-Carmen-Burial-Data-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function activityLog()
    {
        $activity = ActivityLog::with('user')
            ->latest()
            ->paginate(25);

        return view('superadmin.activity-log', compact('activity'));
    }

    public function changelog()
    {
        $year = now()->year;

        $totalPermits = BurialPermit::count();
        $activePermits = BurialPermit::where('status', 'active')->count();
        $expiringPermits = BurialPermit::where('status', 'expiring')->count();
        $expiredPermits = BurialPermit::where('status', 'expired')->count();
        $permitsThisMonth = BurialPermit::whereMonth('created_at', now()->month)
            ->whereYear('created_at', $year)->count();
        $totalDeceased = DeceasedPerson::count();
        $deceasedThisMonth = DeceasedPerson::whereMonth('created_at', now()->month)
            ->whereYear('created_at', $year)->count();
        $newPermits = BurialPermit::whereYear('created_at', $year)->count();
        $renewedPermits = BurialPermit::whereYear('updated_at', $year)
            ->whereYear('created_at', '<', $year)
            ->where('status', 'active')->count();

        $activity = ActivityLog::with('user')
            ->latest()
            ->paginate(20);

        return view('superadmin.changelog', compact(
            'activity',
            'totalPermits',
            'activePermits',
            'expiringPermits',
            'expiredPermits',
            'permitsThisMonth',
            'totalDeceased',
            'deceasedThisMonth',
            'newPermits',
            'renewedPermits'
        ));
    }

    public function geomap()
    {
        $stats = [
            'total'    => \App\Models\CemeteryPlot::count(),
            'occupied' => \App\Models\CemeteryPlot::where('status', 'occupied')->count(),
            'active'   => \App\Models\BurialPermit::where('status', 'active')->count(),
            'expiring' => \App\Models\BurialPermit::where('status', 'expiring')->count(),
            'expired'  => \App\Models\BurialPermit::where('status', 'expired')->count(),
        ];

        return view('superadmin.geomap', compact('stats'));
    }
}
