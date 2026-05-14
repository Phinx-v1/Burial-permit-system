<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use App\Exports\BurialReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private function loadSettings(): array
    {
        $path = storage_path('app/settings.json');
        if (! file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true) ?? [];
    }

    public function index()
    {
        $data = $this->prepareReportData();
        $recentPermits = BurialPermit::with('deceased')->latest()->limit(10)->get();

        return view('reports.index', array_merge($data, [
            'recentPermits' => $recentPermits
        ]));
    }

    public function superAdminIndex()
    {
        $data = $this->prepareReportData();

        return view('superadmin.reports', array_merge($data, [
            'recentPermits' => BurialPermit::with('deceased')->latest()->limit(10)->get()
        ]));
    }

    private function prepareReportData(): array
    {
        $year  = now()->year;
        $month = now()->month;

        $totalPermits    = BurialPermit::count();
        $activePermits   = BurialPermit::where('status', 'active')->count();
        $expiringPermits = BurialPermit::where('status', 'expiring')->count();
        $expiredPermits  = BurialPermit::where('status', 'expired')->count();

        $newPermits       = BurialPermit::whereYear('created_at', $year)->count();
        $permitsThisMonth = BurialPermit::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $permitsThisWeek  = BurialPermit::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        // Improved Renewals Tracking: Use renewal_count > 0 for this year
        $renewedPermits    = BurialPermit::whereYear('updated_at', $year)->where('renewal_count', '>', 0)->count();
        $renewalsThisYear  = $renewedPermits;
        $renewalsThisMonth = BurialPermit::whereYear('updated_at', $year)->whereMonth('updated_at', $month)->where('renewal_count', '>', 0)->count();
        $renewalsThisWeek  = BurialPermit::whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])->where('renewal_count', '>', 0)->count();

        $urgentExpiring = BurialPermit::where('status', 'expiring')->whereNotNull('expiry_date')->whereDate('expiry_date', '>=', now())->whereDate('expiry_date', '<=', now()->addDays(7))->count();
        $expiringSoon   = BurialPermit::where('status', 'expiring')->whereNotNull('expiry_date')->whereDate('expiry_date', '>=', now())->whereDate('expiry_date', '<=', now()->addDays(30))->count();

        $totalDeceased     = DeceasedPerson::count();
        $deceasedThisYear  = DeceasedPerson::whereYear('created_at', $year)->count();
        $deceasedThisMonth = DeceasedPerson::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();

        // Base trend
        $monthly = BurialPermit::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, COUNT(*) as total")
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Status-specific trends for sparklines
        $activeMonthlyRaw = BurialPermit::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, COUNT(*) as total")
            ->where('status', 'active')->whereYear('created_at', $year)
            ->groupBy('month')->pluck('total', 'month')->toArray();
            
        $expiringMonthlyRaw = BurialPermit::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, COUNT(*) as total")
            ->where('status', 'expiring')->whereYear('created_at', $year)
            ->groupBy('month')->pluck('total', 'month')->toArray();

        $expiredMonthlyRaw = BurialPermit::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, COUNT(*) as total")
            ->where('status', 'expired')->whereYear('created_at', $year)
            ->groupBy('month')->pluck('total', 'month')->toArray();

        $monthlyData = [];
        $activeMonthlyData = [];
        $expiringMonthlyData = [];
        $expiredMonthlyData = [];

        $monthNames  = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m - 1] = $monthly[$m] ?? 0;
            $activeMonthlyData[$m - 1] = $activeMonthlyRaw[$m] ?? 0;
            $expiringMonthlyData[$m - 1] = $expiringMonthlyRaw[$m] ?? 0;
            $expiredMonthlyData[$m - 1] = $expiredMonthlyRaw[$m] ?? 0;
        }
        $totalYearlyPermits = array_sum($monthlyData);
        $maxVal             = max(array_merge($monthlyData, [1]));
        $peakIdx            = array_search($maxVal, $monthlyData);
        $busiestMonth       = $monthNames[$peakIdx] ?? '—';
        $busiestCount       = $maxVal;

        $feeCounts = BurialPermit::selectRaw('permit_type, COUNT(*) as total')
            ->whereIn('status', ['active', 'expiring', 'expired'])
            ->groupBy('permit_type')
            ->pluck('total', 'permit_type')
            ->toArray();

        $settings = $this->loadSettings();
        $fees = $settings['fees'] ?? [
            'cemented' => ['tomb'=>910, 'permit'=>20, 'maint'=>50, 'app'=>20],
            'niche_1st' => ['tomb'=>7960, 'permit'=>20, 'maint'=>50, 'app'=>20],
            'niche_2nd' => ['tomb'=>6560, 'permit'=>20, 'maint'=>50, 'app'=>20],
            'niche_3rd' => ['tomb'=>5660, 'permit'=>20, 'maint'=>50, 'app'=>20],
            'niche_4th' => ['tomb'=>5260, 'permit'=>20, 'maint'=>50, 'app'=>20],
            'bone_niches' => ['tomb'=>4960, 'permit'=>20, 'maint'=>50, 'app'=>20]
        ];

        $estimatedRevenue = 0;
        $revenueBreakdown = [];
        $feeLabels = [
            'cemented'    => 'Cemented',
            'niche_1st'   => '1st Floor Niche',
            'niche_2nd'   => '2nd Floor Niche',
            'niche_3rd'   => '3rd Floor Niche',
            'niche_4th'   => '4th Floor Niche',
            'bone_niches' => 'Bone Niches'
        ];

        foreach ($feeLabels as $key => $label) {
            $count = $feeCounts[$key] ?? 0;
            $f = $fees[$key] ?? ($fees['cemented'] ?? []);
            $unitPrice = ($f['tomb']??0) + ($f['permit']??0) + ($f['maint']??0) + ($f['app']??0);
            $totalRev = $count * $unitPrice;
            
            $estimatedRevenue += $totalRev;
            $revenueBreakdown[] = [
                'key'       => $key,
                'label'     => $label,
                'count'     => $count,
                'unitPrice' => $unitPrice,
                'total'     => $totalRev
            ];
        }

        return [
            'totalPermits' => $totalPermits, 'activePermits' => $activePermits, 'expiringPermits' => $expiringPermits, 'expiredPermits' => $expiredPermits,
            'newPermits' => $newPermits, 'permitsThisMonth' => $permitsThisMonth, 'permitsThisWeek' => $permitsThisWeek,
            'renewedPermits' => $renewedPermits, 'renewalsThisYear' => $renewalsThisYear, 'renewalsThisMonth' => $renewalsThisMonth, 'renewalsThisWeek' => $renewalsThisWeek,
            'urgentExpiring' => $urgentExpiring, 'expiringSoon' => $expiringSoon,
            'totalDeceased' => $totalDeceased, 'deceasedThisYear' => $deceasedThisYear, 'deceasedThisMonth' => $deceasedThisMonth,
            'monthlyData' => $monthlyData, 'totalYearlyPermits' => $totalYearlyPermits, 'busiestMonth' => $busiestMonth, 'busiestCount' => $busiestCount,
            'activeMonthlyData' => $activeMonthlyData, 'expiringMonthlyData' => $expiringMonthlyData, 'expiredMonthlyData' => $expiredMonthlyData,
            'feeCounts' => $feeCounts, 'estimatedRevenue' => $estimatedRevenue, 'revenueBreakdown' => $revenueBreakdown,
            'year' => $year
        ];
    }

    public function export()
    {
        $data = $this->prepareReportData();
        $recentPermits = BurialPermit::with('deceased')->latest()->limit(15)->get();

        $pdf = Pdf::loadView('superadmin.pdf_export', array_merge($data, [
            'recentPermits' => $recentPermits
        ]))->setPaper('a4', 'portrait');

        return $pdf->download('LGU-Carmen-Burial-Report-'.now()->format('Y-m-d').'.pdf');
    }

    public function exportExcel()
    {
        $data     = $this->prepareReportData();
        $settings = $this->loadSettings();

        $filename = 'LGU-Carmen-BurialReport-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new BurialReportExport($data, $settings),
            $filename
        );
    }
}