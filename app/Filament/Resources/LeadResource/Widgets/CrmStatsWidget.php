<?php

namespace App\Filament\Resources\LeadResource\Widgets;

use App\Models\Lead;
use App\Models\User;
use App\Models\Campaign;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CrmStatsWidget extends BaseWidget
{
    protected function getCards(): array
    {
        $leadsCount = Lead::count();
        $studentsCount = User::where('role', 'student')->count();
        $campaignsCount = Campaign::count();

        return [
            Card::make('عدد العملاء المحتملين (Leads)', $leadsCount),
            Card::make('عدد الطلاب (Students)', $studentsCount),
            Card::make('عدد الحملات (Campaigns)', $campaignsCount),
        ];
    }
}
