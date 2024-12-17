<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\Relations\Relation;


class UsersWithoutCoursesWidget extends TableWidget
{
    protected static ?string $heading = 'مستخدمون غير مسجلين في أي دورة';

    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder|Relation|null
    {
        // جلب المستخدمين الذين ليس لديهم دورات مسجلين فيها
        // نفترض أن هناك علاقة في User: enrolledCourses()
        return User::whereDoesntHave('enrolledCourses');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('الاسم')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('email')
                ->label('البريد الإلكتروني')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('تاريخ التسجيل')
                ->dateTime('d/m/Y')
                ->sortable(),
        ];
    }
}
