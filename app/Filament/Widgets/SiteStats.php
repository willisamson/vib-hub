<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Norms\NormResource;
use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Trainings\TrainingResource;
use App\Models\Inquiry;
use App\Models\Norm;
use App\Models\Customer;
use App\Models\Training;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $new = Inquiry::query()->where('status', 'neu')->count();
        $nextDates = Training::query()->published()->get()
            ->sum(fn (Training $t): int => $t->upcomingDates()->count());

        return [
            Stat::make('Neue Anfragen', $new)
                ->description(Inquiry::query()->where('created_at', '>=', now()->subDays(30))->count().' in den letzten 30 Tagen')
                ->descriptionIcon(Heroicon::OutlinedInboxArrowDown)
                ->color($new > 0 ? 'warning' : 'success')
                ->url(InquiryResource::getUrl('index')),
            Stat::make('Kundenkonten', Customer::query()->count())
                ->description(Customer::query()->where('created_at', '>=', now()->subDays(30))->count().' neu in den letzten 30 Tagen')
                ->descriptionIcon(Heroicon::OutlinedUserGroup)
                ->url(CustomerResource::getUrl('index')),
            Stat::make('Schulungstermine', $nextDates)
                ->description('anstehend')
                ->descriptionIcon(Heroicon::OutlinedAcademicCap)
                ->url(TrainingResource::getUrl('index')),
            Stat::make('Normen', Norm::query()->published()->count())
                ->description('in der Normen-Übersicht')
                ->descriptionIcon(Heroicon::OutlinedBookOpen)
                ->url(NormResource::getUrl('index')),
        ];
    }
}
