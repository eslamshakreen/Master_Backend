<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Payment;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;
use \Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\Relations\Relation;


class TopSellingCoursesWidget extends TableWidget
{
    protected static ?string $heading = 'أفضل الدورات مبيعاً';

    protected static ?int $sort = 1;

    protected function getTableQuery(): Builder|Relation|null
    {
        // جلب أفضل 5 دورات حسب إجمالي الأرباح
        return Payment::select('course_id', DB::raw('SUM(amount_paid) as total_revenue'))
            ->groupBy('course_id')
            ->orderByDesc('total_revenue')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('course_id')
                ->label('الدورة')
                ->formatStateUsing(function ($state) {
                    $course = Course::find($state);
                    return $course ? $course->title : '-';
                }),

            Tables\Columns\TextColumn::make('total_revenue')
                ->label('الإيرادات')
                ->formatStateUsing(fn($state) => number_format($state, 2) . ' $'),
        ];
    }
}
