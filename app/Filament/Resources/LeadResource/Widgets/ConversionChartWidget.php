<?php

namespace App\Filament\Resources\LeadResource\Widgets;
use App\Models\Lead;
use Carbon\Carbon;
use Filament\Widgets\BarChartWidget;

class ConversionChartWidget extends BarChartWidget
{
    protected static ?string $heading = 'معدل التحويل الشهري';

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        // اخر 6 شهور مثلاً
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $leadsThisMonth = Lead::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $convertedThisMonth = Lead::whereBetween('converted_at', [$monthStart, $monthEnd])->whereNotNull('converted_at')->count();

            $conversionRate = $leadsThisMonth > 0 ? ($convertedThisMonth / $leadsThisMonth) * 100 : 0;

            $labels[] = $month->format('M Y');
            $data[] = round($conversionRate, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'معدل التحويل (%)',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
