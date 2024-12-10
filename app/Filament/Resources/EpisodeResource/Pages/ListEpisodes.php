<?php

namespace App\Filament\Resources\EpisodeResource\Pages;

use App\Filament\Resources\EpisodeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEpisodes extends ListRecords
{
    protected static string $resource = EpisodeResource::class;

    public function getTitle(): string
    {
        return 'إدارة الحلقات';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إنشاء حلقة جديدة'),
        ];
    }
}
