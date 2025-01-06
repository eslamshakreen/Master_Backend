<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('تسجيل في دورة '),

            Actions\Action::make('enrollInCategory')
                ->label('تسجيل طالب في اكادمية')
                ->color('primary')
                ->url(static::getResource()::getUrl('enroll-in-category')),

        ];
    }
}
