<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Guest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::today()->startOfMonth();

        // Counter stats
        $totalToday = Guest::whereDate('visit_date', $today)->count();
        $totalMonth = Guest::whereDate('visit_date', '>=', $startOfMonth)->count();
        $totalAll = Guest::count();

        $waitingCount = Guest::whereDate('visit_date', $today)->where('status', 'menunggu')->count();
        $activeCount = Guest::whereDate('visit_date', $today)->where('status', 'sedang_bertemu')->count();
        $completedToday = Guest::whereDate('visit_date', $today)->where('status', 'selesai')->count();

        // IKM Stats
        $avgRating = Guest::whereNotNull('satisfaction_rating')->avg('satisfaction_rating') ?? 0;
        $totalRated = Guest::whereNotNull('satisfaction_rating')->count();
        $satisfactionPercentage = $avgRating > 0 ? round(($avgRating / 5) * 100, 1) : 0;

        // 7-day visit trends
        $sevenDays = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Guest::whereDate('visit_date', $date->toDateString())->count();
            $sevenDays->push([
                'date' => $date->translatedFormat('d M'),
                'count' => $count,
            ]);
        }

        // Visits per department
        $deptStats = Department::withCount('guests')
            ->orderByDesc('guests_count')
            ->take(6)
            ->get();

        // Visits per city/regency
        $cityStats = Guest::select('city_regency', DB::raw('count(*) as total'))
            ->groupBy('city_regency')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        // Recent guests today
        $recentGuests = Guest::with('department')
            ->whereDate('visit_date', $today)
            ->latest('id')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalToday',
            'totalMonth',
            'totalAll',
            'waitingCount',
            'activeCount',
            'completedToday',
            'avgRating',
            'totalRated',
            'satisfactionPercentage',
            'sevenDays',
            'deptStats',
            'cityStats',
            'recentGuests'
        ));
    }
}
