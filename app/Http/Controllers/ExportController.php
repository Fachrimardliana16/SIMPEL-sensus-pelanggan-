<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function downloadSensusPdf(Request $request)
    {
        $query = SurveyResponse::query();

        // Filter by Date Range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
            $period = $request->start_date . ' s/d ' . $request->end_date;
        } else {
            $period = 'Seluruh Waktu';
        }

        // Filter by Surveyor
        if ($request->has('surveyor_id') && $request->surveyor_id) {
            $query->where('surveyor_id', $request->surveyor_id);
            $surveyor = User::find($request->surveyor_id);
            $surveyor_name = $surveyor ? $surveyor->name : 'N/A';
        } else {
            $surveyor_name = 'Semua Surveyor';
        }

        $data = $query->latest()->get();

        $pdf = Pdf::loadView('exports.sensus-pdf', [
            'data' => $data,
            'period' => $period,
            'surveyor_name' => $surveyor_name,
        ])->setPaper('a4', 'landscape');

        $filename = 'Laporan_Sensus_' . now()->format('YmdHis') . '.pdf';
        
        return $pdf->download($filename);
    }
    public function printSingleSensus(Request $request, string $id)
    {
        $record = SurveyResponse::with(['surveyor'])->findOrFail($id);

        // Gate: Analyst can print any, Surveyor only their own
        if (
            ! auth()->user()->hasRole(['Admin', 'Super Admin', 'Analyst']) &&
            $record->surveyor_id !== auth()->id()
        ) {
            abort(403, 'Unauthorized');
        }

        $pdf = Pdf::loadView('reports.census-single-pdf', [
            'record' => $record,
        ])->setPaper('a4', 'portrait');

        $filename = 'Sensus_' . ($record->nolangg ?? $id) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
