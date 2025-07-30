<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Model Data
use App\Models\Report\Reports;
use App\Models\User;
use Carbon\Carbon;

class dashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika admin, tampilkan dashboard admin
        if (strtolower($user->role) === 'admin') {
            return $this->adminDashboard();
        }

        if (strtolower($user->role) === 'qshe') {
            return $this->qsheDashboard();
        }

        if (strtolower($user->is_pic) === '1') {
           return $this->picDashboard();
        }

        return view('dashboards.general');
    }

    protected function adminDashboard($selectedYear = null)
    {
        $selectedYear = $selectedYear ?? Carbon::now()->year;

        $reports = Reports::with(['pelapor', 'division'])->get();

        // Statistik dasar
        $totalReports = $reports->count();
        $closedReports = $reports->where('status', 'closed')->count();
        $pendingReports = $reports->where('status', 'open')->count();
        $onProgressReports = $reports->where('status', 'assigned_to_division')->count();

        $statuses = ['open', 'assigned_to_division', 'closed'];

        $bahayaDataRaw = DB::table('reports')
            ->join('divisions', 'reports.division_id', '=', 'divisions.id')
            ->where('reports.bahaya_id', 'potensi bahaya') // ganti sesuai kebutuhan
            ->select('divisions.name as division', 'reports.status', DB::raw('COUNT(*) as total'))
            ->groupBy('divisions.name', 'reports.status')
            ->get();

        // Siapkan struktur data
        $divisions = [];
        $seriesData = [
            'open' => [],
            'assigned_to_division' => [],
            'closed' => [],
        ];

        // Susun data per divisi
        foreach ($bahayaDataRaw as $row) {
            $div = $row->division;
            $status = $row->status;
            $count = $row->total;

            if (!in_array($div, $divisions)) {
                $divisions[] = $div;
            }

            foreach ($statuses as $s) {
                if (!isset($seriesData[$s][$div])) {
                    $seriesData[$s][$div] = 0;
                }
            }

            $seriesData[$status][$div] = $count;
        }

        // Buat format untuk ApexCharts
        $series = [];
        foreach ($statuses as $status) {
            $series[] = [
                'name' => ucfirst(str_replace('_', ' ', $status)),
                'data' => array_map(function ($division) use ($seriesData, $status) {
                    return $seriesData[$status][$division] ?? 0;
                }, $divisions),
            ];
        }

        // Laporan terbaru 
        $recentReports = Reports::with(['pelapor', 'division'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // Data laporan per bulan untuk tahun tertentu
        $monthlyLabels = [];
        $monthlyReportData = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthlyLabels[] = Carbon::create()->month($i)->format('M');
            $monthlyReportData[] = Reports::whereMonth('created_at', $i)
                                        ->whereYear('created_at', $selectedYear)
                                        ->count();
        }

        // Kategori laporan
        $kategoriData = DB::table('reports')
                        ->join('locations', 'reports.location_id', '=', 'locations.id')
                        ->select('locations.nama_lokasi', DB::raw('COUNT(*) as total'))
                        ->groupBy('locations.nama_lokasi')
                        ->pluck('total', 'locations.nama_lokasi')
                        ->toArray();
                        

        // Ambil semua tahun tersedia
        $availableYears = Reports::selectRaw('YEAR(created_at) as year')
                                ->distinct()
                                ->orderByDesc('year')
                                ->pluck('year')
                                ->toArray();

        return view('dashboards.index', [
            'totalReports' => $totalReports,
            'closedReports' => $closedReports,
            'pendingReports' => $pendingReports,
            'onProgressReports' => $onProgressReports,
            'recentReports' => $recentReports,
            'monthlyLabels' => $monthlyLabels,
            'monthlyReportData' => $monthlyReportData,
            'kategoriData' => $kategoriData,
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear,
            'bahayaDivisions' => $divisions,
            'bahayaSeries' => $series,
        ]);
    }

    // Qshe Dashboad
    protected function qsheDashboard($selectedYear = null)
    {
        $selectedYear = $selectedYear ?? Carbon::now()->year;

        $reports = Reports::with(['pelapor', 'division'])->get();

        // Statistik untuk QSHE
        $totalReports = $reports->count();
        $ditinjau = $reports->where('status', 'open')->count();
        $reviewedReports = $reports->where('status', 'under_review_by_qshe')->count();
        $rejectedByQshe = $reports->where('status', 'rejected_by_qshe')->count();
        $followUpReports = $reports->where('status', 'closed')->count();

        // Laporan terbaru 
        $recentReports = Reports::with(['pelapor', 'division'])
                            ->where('status', 'open')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        $statuses = ['open', 'assigned_to_division', 'closed'];

        $bahayaDataRaw = DB::table('reports')
            ->join('divisions', 'reports.division_id', '=', 'divisions.id')
            ->where('reports.bahaya_id', 'potensi bahaya') // ganti sesuai kebutuhan
            ->select('divisions.name as division', 'reports.status', DB::raw('COUNT(*) as total'))
            ->groupBy('divisions.name', 'reports.status')
            ->get();

        // Siapkan struktur data
        $divisions = [];
        $seriesData = [
            'open' => [],
            'assigned_to_division' => [],
            'closed' => [],
        ];

        // Susun data per divisi
        foreach ($bahayaDataRaw as $row) {
            $div = $row->division;
            $status = $row->status;
            $count = $row->total;

            if (!in_array($div, $divisions)) {
                $divisions[] = $div;
            }

            foreach ($statuses as $s) {
                if (!isset($seriesData[$s][$div])) {
                    $seriesData[$s][$div] = 0;
                }
            }

            $seriesData[$status][$div] = $count;
        }

        // Buat format untuk ApexCharts
        $series = [];
        foreach ($statuses as $status) {
            $series[] = [
                'name' => ucfirst(str_replace('_', ' ', $status)),
                'data' => array_map(function ($division) use ($seriesData, $status) {
                    return $seriesData[$status][$division] ?? 0;
                }, $divisions),
            ];
        }

        // Data laporan per bulan untuk tahun tertentu
        $monthlyLabels = [];
        $monthlyReportData = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthlyLabels[] = \Carbon\Carbon::create()->month($i)->format('M');
            $monthlyReportData[] = Reports::whereMonth('created_at', $i)
                                        ->whereYear('created_at', $selectedYear)
                                        ->count();
        }

        // Kategori berdasarkan lokasi (lokasi laporan)
        $kategoriData = DB::table('reports')
                        ->join('locations', 'reports.location_id', '=', 'locations.id')
                        ->select('locations.nama_lokasi', DB::raw('COUNT(*) as total'))
                        ->groupBy('locations.nama_lokasi')
                        ->pluck('total', 'locations.nama_lokasi')
                        ->toArray();

        // Tahun tersedia
        $availableYears = Reports::selectRaw('YEAR(created_at) as year')
                                ->distinct()
                                ->orderByDesc('year')
                                ->pluck('year')
                                ->toArray();

        return view('dashboards.index', [
            'totalReports' => $totalReports,
            'reviewedReports' => $reviewedReports,
            'ditinjau' => $ditinjau,
            'rejectedByQshe' => $rejectedByQshe,
            'followUpReports' => $followUpReports,
            'recentReports' => $recentReports,
            'monthlyLabels' => $monthlyLabels,
            'monthlyReportData' => $monthlyReportData,
            'kategoriData' => $kategoriData,
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear,
            'bahayaDivisions' => $divisions,
            'bahayaSeries' => $series,
        ]);
    }

    protected function picDashboard($selectedYear = null)
    {
        $user = Auth::user();
        $selectedYear = $selectedYear ?? Carbon::now()->year;

        $reports = Reports::with(['pelapor', 'division'])->get();

        // Statistik untuk QSHE
        $totalReports = $reports->where('division_id', $user->division_id)->count();
        $ditinjau = $reports->where('status', 'open')->where('division_id', $user->division_id)->count();
        $reviewedReports = $reports->where('status', 'under_review_by_qshe')->where('division_id', $user->division_id)->count();
        $rejectedByQshe = $reports->where('status', 'rejected_by_qshe')->where('division_id', $user->division_id)->count();
        $followUpReports = $reports->where('status', 'closed')->where('division_id', $user->division_id)->count();

        // Laporan terbaru 
        $recentReports = Reports::with(['pelapor', 'division'])
                            ->where('status', 'open')
                            ->where('division_id', $user->division_id)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // Data laporan per bulan untuk tahun tertentu
        $monthlyLabels = [];
        $monthlyReportData = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthlyLabels[] = \Carbon\Carbon::create()->month($i)->format('M');
            $monthlyReportData[] = Reports::whereMonth('created_at', $i)
                                        ->whereYear('created_at', $selectedYear)
                                        ->count();
        }

        // Kategori berdasarkan lokasi (lokasi laporan)
        $kategoriData = DB::table('reports')
                        ->join('locations', 'reports.location_id', '=', 'locations.id')
                        ->select('locations.nama_lokasi', DB::raw('COUNT(*) as total'))
                        ->groupBy('locations.nama_lokasi')
                        ->pluck('total', 'locations.nama_lokasi')
                        ->toArray();

        // Tahun tersedia
        $availableYears = Reports::selectRaw('YEAR(created_at) as year')
                                ->distinct()
                                ->orderByDesc('year')
                                ->pluck('year')
                                ->toArray();

        return view('dashboards.index', [
            'totalReports' => $totalReports,
            'reviewedReports' => $reviewedReports,
            'ditinjau' => $ditinjau,
            'rejectedByQshe' => $rejectedByQshe,
            'followUpReports' => $followUpReports,
            'recentReports' => $recentReports,
            'monthlyLabels' => $monthlyLabels,
            'monthlyReportData' => $monthlyReportData,
            'kategoriData' => $kategoriData,
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear
        ]);
    }

}
