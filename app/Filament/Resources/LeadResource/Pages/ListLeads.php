<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LeadsImport;
use Filament\Notifications\Notification;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('importLeads')
                ->label('استيراد عميل جديد')
                ->icon('heroicon-o-arrow-down-on-square')
                ->color('primary')
                ->button()
                ->form([
                    FileUpload::make('attachment')
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);
                    Excel::import(new LeadsImport, $file);

                    Notification::make()
                        ->title('تم استيراد العملاء بنجاح')
                        ->success()
                        ->send();
                }),
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
