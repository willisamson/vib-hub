<?php

namespace App\Filament\Resources\MenuItems\Pages;

use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Support\Catalog;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuItem extends CreateRecord
{
    protected static string $resource = MenuItemResource::class;

    protected function fillForm(): void
    {
        parent::fillForm();

        $location = request()->query('location');
        if (is_string($location) && array_key_exists($location, Catalog::MENU_LOCATIONS)) {
            $this->data['location'] = $location;
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['tab' => $this->record->location]);
    }
}
