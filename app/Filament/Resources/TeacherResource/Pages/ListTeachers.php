<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeachers extends ListRecords
{
    protected static string $resource = TeacherResource::class;

    public function getTitle(): string
    {
        return 'إدارة المدرسين';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إنشاء مدرس جديد'),
        ];
    }
}
