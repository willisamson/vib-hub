<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditInquiry extends EditRecord
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label('Per E-Mail antworten')
                ->icon(Heroicon::OutlinedEnvelope)
                ->url(fn (Inquiry $record): string => 'mailto:'.$record->email.'?subject='.rawurlencode('Ihre Anfrage bei vib-hub'))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }

    /** Zeitpunkt der Antwort merken, wenn sich der Antworttext ändert. */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (array_key_exists('reply', $data) && trim((string) $data['reply']) !== trim((string) $this->record->reply)) {
            $data['replied_at'] = filled($data['reply']) ? now() : null;
        }

        return $data;
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
