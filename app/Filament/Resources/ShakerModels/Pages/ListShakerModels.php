<?php

namespace App\Filament\Resources\ShakerModels\Pages;

use App\Filament\Resources\ShakerModels\ShakerModelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShakerModels extends ListRecords
{
    protected static string $resource = ShakerModelResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
