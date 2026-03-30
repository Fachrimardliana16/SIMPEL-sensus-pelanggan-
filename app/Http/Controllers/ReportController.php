<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function downloadCensusPdf(Request $request)
    {
        $query = SurveyResponse::query()->with('surveyor');

        // Filter by surveyor if not admin/analyst
        if (!auth()->user()->hasRole(['Super Admin', 'Admin', 'Analyst'])) {
            $query->where('surveyor_id', auth()->id());
        }

        // Apply filters from request if any
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('census_status', $request->status);
        }

        if ($request->has('surveyor_id')) {
            $query->where('surveyor_id', $request->surveyor_id);
        }

        $records = $query->latest()->get();

        $pdf = Pdf::loadView('reports.census-pdf', [
            'records' => $records,
            'title' => 'Laporan Hasil Sensus Pelanggan',
            'surveyor' => auth()->user()->hasRole(['Surveyor']) ? auth()->user()->name : null,
        ]);

        return $pdf->download('laporan-sensus-' . now()->format('YmdHis') . '.pdf');
    }
}
