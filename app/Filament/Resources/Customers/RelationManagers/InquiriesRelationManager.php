<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use App\Support\Catalog;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/** Anfragen des Kunden auf der Kundenseite im Admin. */
class InquiriesRelationManager extends RelationManager
{
    protected static string $relationship = 'inquiries';

    protected static ?string $title = 'Anfragen';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Eingang')->dateTime('d.m.Y H:i'),
                TextColumn::make('topic')->label('Thema')->badge()->color('gray')
                    ->formatStateUsing(fn (?string $state): string => Catalog::label(Catalog::INQUIRY_TOPICS, $state)),
                TextColumn::make('reference')->label('Bezug')->limit(50)->placeholder('–'),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn (?string $state): string => InquiryResource::statusColor($state))
                    ->formatStateUsing(fn (?string $state): string => Catalog::label(Catalog::INQUIRY_STATUSES, $state)),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('Öffnen')
                    ->icon(Heroicon::OutlinedArrowRight)
                    ->url(fn (Inquiry $record): string => InquiryResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
