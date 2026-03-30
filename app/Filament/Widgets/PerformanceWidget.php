<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PerformanceWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $user = auth()->user();
        
        if ($user->hasRole('Surveyor')) {
            return [
                Stat::make('Sensus Saya (Hari Ini)', SurveyResponse::where('surveyor_id', $user->id)->whereDate('created_at', Carbon::today())->count())
                    ->description('Total kiriman Anda hari ini')
                    ->descriptionIcon('heroicon-m-clipboard-document-check')
                    ->color('info'),
                Stat::make('Sudah Valid', SurveyResponse::where('surveyor_id', $user->id)->where('census_status', 'valid')->count())
                    ->description('Data Anda yang sudah disetujui')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),
                Stat::make('Perlu Revisi', SurveyResponse::where('surveyor_id', $user->id)->where('census_status', 'revisi')->count())
                    ->description('Data yang harus Anda perbaiki')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger'),
            ];
        }

        if ($user->hasRole('Analyst')) {
            return [
                Stat::make('Review Hari Ini', SurveyResponse::whereNotNull('census_status')->whereDate('updated_at', Carbon::today())->count())
                    ->description('Total data yang Anda proses hari ini')
                    ->descriptionIcon('heroicon-m-arrow-path')
                    ->color('success'),
                Stat::make('Menunggu Review', SurveyResponse::where('census_status', 'pending')->count())
                    ->description('Total antrian sisa')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
                Stat::make('Total Validasi', SurveyResponse::where('census_status', 'valid')->count())
                    ->description('Akumulasi data valid')
                    ->descriptionIcon('heroicon-m-shield-check')
                    ->color('info'),
            ];
        }

        return [];
    }
}
