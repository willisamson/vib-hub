<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use App\Support\Catalog;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestInquiries extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Neueste Anfragen')
            ->query(fn () => Inquiry::query()->latest()->limit(8))
            ->paginated(false)
            ->columns([
                TextColumn::make('created_at')->label('Eingang')->since(),
                TextColumn::make('name')->label('Name')->description(fn (Inquiry $record): ?string => $record->company),
                TextColumn::make('topic')->label('Thema')->badge()->color('gray')
                    ->formatStateUsing(fn (?string $state): string => Catalog::label(Catalog::INQUIRY_TOPICS, $state)),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn (?string $state): string => InquiryResource::statusColor($state))
                    ->formatStateUsing(fn (?string $state): string => Catalog::label(Catalog::INQUIRY_STATUSES, $state)),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('Öffnen')
                    ->url(fn (Inquiry $record): string => InquiryResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('Noch keine Anfragen');
    }
}
