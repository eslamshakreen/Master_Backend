<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    public function getTitle(): string
    {
        return 'إدارة الدورات';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إنشاء دورة جديدة'),
        ];
    }
}
