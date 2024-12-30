<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\LeadResource\Widgets\CrmStatsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [

            \App\Filament\Resources\LeadResource\Widgets\ConversionChartWidget::class,
            \App\Filament\Resources\LeadResource\Widgets\RecentCallsWidget::class, // جدول اخر المكالمات
        ];
    }


}
