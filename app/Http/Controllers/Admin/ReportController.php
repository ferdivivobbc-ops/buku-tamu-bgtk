<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Guest;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->query('year', Carbon::now()->year);
        $month = $request->query('month', Carbon::now()->month);

        // Monthly stats breakdown by day
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $dailyData = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = Carbon::create($year, $month, $d)->toDateString();
            $count = Guest::whereDate('visit_date', $date)->count();
            $dailyData[] = [
                'day' => $d,
                'count' => $count,
            ];
        }

        // Yearly stats breakdown by month
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $count = Guest::whereYear('visit_date', $year)
                          ->whereMonth('visit_date', $m)
                          ->count();
            $monthlyData[] = [
                'month' => Carbon::create($year, $m, 1)->translatedFormat('F'),
                'count' => $count,
            ];
        }

        // Total guest counts for selected period
        $totalPeriod = Guest::whereYear('visit_date', $year)
            ->when($month, function ($q) use ($month) {
                return $q->whereMonth('visit_date', $month);
            })
            ->count();

        // City/Regency distribution
        $cityDistribution = Guest::select('city_regency', DB::raw('count(*) as total'))
            ->whereYear('visit_date', $year)
            ->when($month, fn($q) => $q->whereMonth('visit_date', $month))
            ->groupBy('city_regency')
            ->orderByDesc('total')
            ->get();

        // Department distribution
        $deptDistribution = Department::withCount(['guests' => function ($q) use ($year, $month) {
            $q->whereYear('visit_date', $year)
              ->when($month, fn($sub) => $sub->whereMonth('visit_date', $month));
        }])->orderByDesc('guests_count')->get();

        return view('admin.reports.index', compact(
            'year',
            'month',
            'dailyData',
            'monthlyData',
            'totalPeriod',
            'cityDistribution',
            'deptDistribution'
        ));
    }

    public function satisfaction(Request $request)
    {
        $year = $request->query('year', Carbon::now()->year);

        $ratedGuests = Guest::whereNotNull('satisfaction_rating')
            ->whereYear('visit_date', $year)
            ->latest('rated_at');

        $totalRespondents = (clone $ratedGuests)->count();
        $avgScore = (clone $ratedGuests)->avg('satisfaction_rating') ?? 0;

        // Rating distribution counts (5, 4, 3, 2, 1)
        $ratingCounts = [
            5 => (clone $ratedGuests)->where('satisfaction_rating', 5)->count(),
            4 => (clone $ratedGuests)->where('satisfaction_rating', 4)->count(),
            3 => (clone $ratedGuests)->where('satisfaction_rating', 3)->count(),
            2 => (clone $ratedGuests)->where('satisfaction_rating', 2)->count(),
            1 => (clone $ratedGuests)->where('satisfaction_rating', 1)->count(),
        ];

        // IKM conversion (standard scale 25 - 100)
        // Nilai Rata-Rata Tertimbang x 20 (atau skala 25-100)
        $ikmIndex = $totalRespondents > 0 ? round(($avgScore / 5) * 100, 2) : 0;
        $ikmQuality = match (true) {
            $ikmIndex >= 88.31 => ['A', 'Sangat Baik', 'text-emerald-600 bg-emerald-50 border-emerald-200'],
            $ikmIndex >= 76.61 => ['B', 'Baik', 'text-blue-600 bg-blue-50 border-blue-200'],
            $ikmIndex >= 65.00 => ['C', 'Kurang Baik', 'text-amber-600 bg-amber-50 border-amber-200'],
            default => ['D', 'Tidak Baik', 'text-rose-600 bg-rose-50 border-rose-200'],
        };

        $recentFeedbacks = Guest::whereNotNull('satisfaction_feedback')
            ->whereYear('visit_date', $year)
            ->latest('rated_at')
            ->take(20)
            ->get();

        return view('admin.reports.satisfaction', compact(
            'year',
            'totalRespondents',
            'avgScore',
            'ratingCounts',
            'ikmIndex',
            'ikmQuality',
            'recentFeedbacks'
        ));
    }
}
