<?php

namespace App\Filament\Resources\MenuItems\Pages;

use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Models\MenuItem;
use App\Support\Catalog;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMenuItems extends ListRecords
{
    protected static string $resource = MenuItemResource::class;

    protected ?string $subheading = 'Reihenfolge per Ziehen ändern (Symbol rechts oben in der Tabelle). Punkte, deren Ziel nicht erreichbar ist (z. B. App ohne Store-Link), werden automatisch ausgeblendet.';

    public function getTabs(): array
    {
        return collect(Catalog::MENU_LOCATIONS)
            ->map(fn (string $label, string $key) => Tab::make($label)
                ->badge(MenuItem::query()->where('location', $key)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('location', $key)))
            ->all();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(fn (): string => MenuItemResource::getUrl('create', ['location' => $this->activeTab])),
        ];
    }
}
