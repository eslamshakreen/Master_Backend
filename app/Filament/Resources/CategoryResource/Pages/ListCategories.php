<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    public function getTitle(): string
    {
        return 'إدارة التصنيفات';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إنشاء تصنيف جديد'),
        ];
    }
}
