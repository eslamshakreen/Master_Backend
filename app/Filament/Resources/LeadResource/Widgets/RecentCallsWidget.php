<?php

namespace App\Filament\Resources\LeadResource\Widgets;
use App\Models\Call;
use App\Models\Lead;
use App\Models\User;
use Filament\Tables;
use Filament\Widgets\TableWidget;

class RecentCallsWidget extends TableWidget
{
    protected static ?string $heading = 'أحدث المكالمات';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // آخر 10 مكالمة
        return Call::query()->latest()->take(10);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('lead.name')->label('العميل المحتمل'),
            Tables\Columns\TextColumn::make('staff.name')->label('الموظف')->formatStateUsing(fn($record) => $record->staff->name ?? 'N/A'),
            Tables\Columns\TextColumn::make('result')->label('النتيجة'),
            Tables\Columns\TextColumn::make('created_at')->label('تاريخ المكالمة')->dateTime(),
        ];
    }
}
