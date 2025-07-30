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
            'selectedYear' => $selectedYear
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
