<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;
use App\Models\Course;
use App\Models\Payment; // إذا كان لديك مدفوعات
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected function getCards(): array
    {
        $usersCount = User::count();
        $coursesCount = Course::count();
        $totalEarnings = Payment::sum('paid_amount'); // أفترض لديك جدول payments

        return [
            Card::make('عدد المستخدمين', $usersCount)
                ->description('إجمالي المستخدمين المسجلين')
                ->icon('heroicon-o-user-group'),

            Card::make('عدد الدورات', $coursesCount)
                ->description('عدد الدورات المتاحة')
                ->icon('heroicon-o-academic-cap'),

            Card::make('إجمالي الأرباح', number_format($totalEarnings, 2) . ' $')
                ->description('إجمالي المبيعات عبر المنصة')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),
        ];
    }
}
