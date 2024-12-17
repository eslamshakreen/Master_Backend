<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\BarChartWidget;
use App\Models\User;

class MonthlyRegistrationsChartWidget extends BarChartWidget
{
    protected static ?string $heading = 'التسجيلات الشهرية';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        // احسب عدد المستخدمين المسجلين لكل شهر في آخر 6 أشهر
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = User::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $labels[] = $month->format('M Y');
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'عدد المستخدمين',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
