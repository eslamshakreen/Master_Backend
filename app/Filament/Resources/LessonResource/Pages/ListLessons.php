<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLessons extends ListRecords
{
    protected static string $resource = LessonResource::class;

    public function getTitle(): string
    {
        return 'إدارة الدروس';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إنشاء درس جديد'),
        ];
    }
}
