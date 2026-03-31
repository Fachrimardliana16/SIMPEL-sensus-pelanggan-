<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicDashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getDashboardData($request->get('filter', 'daily'));
        return view('welcome', $data);
    }

    public function getApiStats(Request $request)
    {
        return response()->json($this->getDashboardData($request->get('filter', 'daily')));
    }

    protected function getDashboardData($filter)
    {
        return \Illuminate\Support\Facades\Cache::remember('dashboard_stats_' . $filter, now()->addMinutes(5), function() use ($filter) {
            $totalTarget = Customer::count();
            $totalSurveyed = SurveyResponse::count();
        
        $statValid = SurveyResponse::where('census_status', 'valid')->count();
        $statReview = SurveyResponse::whereIn('census_status', ['pending', 'revisi'])->count();
        $statBelumSurvey = max(0, $totalTarget - $totalSurveyed);

        // Pelanggan Status Counts (Mapping from MasterDataSeeder)
        // 2: Aktif, 4: Tutup Sementara, 3: Tutup (Tetap), 5: Bongkar
        $statAktif = Customer::where('status', '2')->count();
        $statTutupSem = Customer::where('status', '4')->count();
        $statTutupTetap = Customer::where('status', '3')->count();
        $statBongkar = Customer::where('status', '5')->count();

        // Completion Percentage
        $percCompletion = $totalTarget > 0 ? round(($totalSurveyed / $totalTarget) * 100, 1) : 0;

        // Trend Analysis
        $trendAnalysis = $this->calculateTrend($filter);

        // Top Surveyor
        $topSurveyor = User::role('Surveyor')
            ->withCount(['surveyResponses' => function($q) {
                $q->from('survey_responses');
            }])
            ->orderByDesc('survey_responses_count')
            ->first();

        // Average Points
        $avgPoints = round(SurveyResponse::avg('total_points') ?? 0, 1);

        // Map Data — anonymized (only coordinates + status, no names)
        $mapData = SurveyResponse::select(['lati', 'longi', 'census_status'])
            ->whereNotNull('lati')
            ->whereNotNull('longi')
            ->where('lati', '!=', 0)
            ->get()
            ->map(function($item) {
                $lat = (float) $item->lati;
                $lng = (float) $item->longi;
                if (abs($lat) > 1000) $lat = $lat / 1000000000000000;
                if (abs($lng) > 1000) $lng = $lng / 1000000000000000;
                return [
                    'lat' => $lat,
                    'lng' => $lng,
                    'status' => $item->census_status,
                ];
            });

        // Chart Data
        if ($filter === 'monthly') {
            $trend = SurveyResponse::select(
                DB::raw("strftime('%Y-%m', created_at) as date"),
                DB::raw('count(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(12)
            ->get()
            ->reverse();
        } else {
            $trend = SurveyResponse::select(
                DB::raw("date(created_at) as date"),
                DB::raw('count(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(14)
            ->get()
            ->reverse();
        }

        return [
            'totalTarget' => $totalTarget,
            'totalSurveyed' => $totalSurveyed,
            'statValid' => $statValid,
            'statReview' => $statReview,
            'statBelumSurvey' => $statBelumSurvey,
            'statAktif' => $statAktif,
            'statTutupSem' => $statTutupSem,
            'statTutupTetap' => $statTutupTetap,
            'statBongkar' => $statBongkar,
            'percCompletion' => $percCompletion,
            'trendAnalysis' => $trendAnalysis,
            'topSurveyor' => $topSurveyor ? $topSurveyor->name : '-',
            'avgPoints' => $avgPoints,
            'mapData' => $mapData,
            'chartLabels' => $trend->pluck('date'),
            'chartValues' => $trend->pluck('count'),
            'currentFilter' => $filter,
        ];
        });
    }

    protected function calculateTrend($filter)
    {
        if ($filter === 'monthly') {
            $current = SurveyResponse::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();
            $previous = SurveyResponse::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->count();
        } else {
            $current = SurveyResponse::whereDate('created_at', Carbon::today())->count();
            $previous = SurveyResponse::whereDate('created_at', Carbon::yesterday())->count();
        }

        if ($previous == 0) {
            return $current > 0 ? ['type' => 'up', 'value' => 100, 'text' => 'Kenaikan Signifikan'] : ['type' => 'neutral', 'value' => 0, 'text' => 'Stabil'];
        }

        $diff = (($current - $previous) / $previous) * 100;
        return [
            'type' => $diff > 0 ? 'up' : ($diff < 0 ? 'down' : 'neutral'),
            'value' => abs(round($diff, 1)),
            'text' => $diff > 0 ? 'Kenaikan' : ($diff < 0 ? 'Penurunan' : 'Stabil'),
            'current' => $current,
            'previous' => $previous
        ];
    }
}
