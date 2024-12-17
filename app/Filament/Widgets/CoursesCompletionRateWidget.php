<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CoursesCompletionRateWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('completion_status', true)->count();
        $completionRate = $totalEnrollments > 0
            ? ($completedEnrollments / $totalEnrollments) * 100
            : 0;

        return [
            Card::make('نسبة إتمام الدورات', number_format($completionRate, 2) . '%')
                ->description("إجمالي: $totalEnrollments تسجيلات، مكتمل: $completedEnrollments")
                ->icon('heroicon-o-check-circle')
                ->color($completionRate >= 50 ? 'success' : 'warning'),
        ];
    }
}
