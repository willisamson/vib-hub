<?php

namespace App\Filament\Resources\ShakerModels\Pages;

use App\Filament\Resources\ShakerModels\ShakerModelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShakerModel extends EditRecord
{
    protected static string $resource = ShakerModelResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
