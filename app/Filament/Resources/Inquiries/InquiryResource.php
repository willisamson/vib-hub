<?php

namespace App\Filament\Resources\Inquiries;

use App\Filament\Resources\Inquiries\Pages\EditInquiry;
use App\Filament\Resources\Inquiries\Pages\ListInquiries;
use App\Models\Inquiry;
use Filament\Tables\Columns\IconColumn;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use App\Support\Site;
use App\Support\PartnerForwarder;
use App\Support\Catalog;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|UnitEnum|null $navigationGroup = 'Kunden';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Anfrage';

    protected static ?string $pluralModelLabel = 'Anfragen';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        $count = Inquiry::query()->where('status', 'neu')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function statusColor(?string $state): string
    {
        return match ($state) {
            'neu' => 'warning',
            'in_bearbeitung' => 'info',
            'angebot' => 'primary',
            'erledigt' => 'success',
            default => 'gray',
        };
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Anfrage')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('name')->label('Name')->disabled(),
                    TextInput::make('company')->label('Firma')->disabled(),
                    TextInput::make('email')->label('E-Mail')->disabled(),
                    TextInput::make('phone')->label('Telefon')->disabled(),
                    Select::make('topic')->label('Thema')->options(Catalog::INQUIRY_TOPICS)->disabled(),
                    TextInput::make('reference')->label('Bezug')->disabled(),
                    Textarea::make('message')->label('Nachricht')->rows(8)->disabled()->columnSpanFull(),
                ]),
            Section::make('Bearbeitung')
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('customer_info')
                        ->label('Kundenkonto')
                        ->state(fn (?Inquiry $record): string => $record?->customer
                            ? $record->customer->name.' ('.$record->customer->email.') – sieht Status und Antwort unter „Mein Konto“'
                            : 'Ohne Kundenkonto gestellt – Antwort bitte per E-Mail.'),
                    TextEntry::make('forward_info')
                        ->label('Weiterleitung an Anlagenpartner')
                        ->state(fn (?Inquiry $record): string => match (true) {
                            ! $record?->forward_consent => 'Keine Zustimmung – nicht weiterleiten.',
                            (bool) $record->forwarded_at => 'Weitergeleitet an '.$record->forwarded_to.' am '.$record->forwarded_at->format('d.m.Y H:i').'.',
                            default => 'Kunde hat zugestimmt – noch nicht weitergeleitet (Aktion „An Partner weiterleiten“ in der Liste).',
                        })
                        ->visible(fn (?Inquiry $record): bool => $record?->topic === 'pruefstand'),
                    Select::make('status')->label('Status')->options(Catalog::INQUIRY_STATUSES)->required()
                        ->helperText('Kunden mit Konto sehen den Status in ihrem Konto.'),
                    Textarea::make('reply')->label('Antwort an den Kunden')->rows(5)
                        ->helperText('Erscheint im Kundenkonto bei dieser Anfrage. Nur sichtbar, wenn die Anfrage über ein Kundenkonto gestellt wurde.')
                        ->visible(fn (?Inquiry $record): bool => (bool) $record?->customer_id),
                    Textarea::make('notes')->label('Interne Notizen')->rows(4)->helperText('Nur im Admin sichtbar.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Eingang')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('name')->label('Name')->searchable()->weight(FontWeight::Bold)
                    ->description(fn (Inquiry $record): ?string => $record->company)
                    ->icon(fn (Inquiry $record) => $record->customer_id ? Heroicon::OutlinedUserCircle : null)
                    ->iconColor('primary')
                    ->tooltip(fn (Inquiry $record): ?string => $record->customer_id ? 'Kunde mit Konto' : null),
                TextColumn::make('email')->label('E-Mail')->searchable()->copyable(),
                TextColumn::make('topic')->label('Thema')->badge()->color('gray')
                    ->formatStateUsing(fn (?string $state): string => Catalog::label(Catalog::INQUIRY_TOPICS, $state)),
                IconColumn::make('forwarded_at')->label('Partner')
                    ->state(fn (Inquiry $record): ?string => $record->forward_consent ? ($record->forwarded_at ? 'sent' : 'open') : null)
                    ->icon(fn (?string $state) => match ($state) { 'sent' => Heroicon::OutlinedCheckCircle, 'open' => Heroicon::OutlinedPaperAirplane, default => null })
                    ->color(fn (?string $state): string => $state === 'sent' ? 'success' : 'warning')
                    ->tooltip(fn (Inquiry $record): ?string => $record->forward_consent ? ($record->forwarded_at ? 'An Partner weitergeleitet' : 'Weiterleitung erlaubt, noch offen') : null),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn (?string $state): string => static::statusColor($state))
                    ->formatStateUsing(fn (?string $state): string => Catalog::label(Catalog::INQUIRY_STATUSES, $state)),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options(Catalog::INQUIRY_STATUSES),
                SelectFilter::make('topic')->label('Thema')->options(Catalog::INQUIRY_TOPICS),
                TernaryFilter::make('customer_id')->label('Kundenkonto')->nullable()
                    ->trueLabel('Mit Kundenkonto')->falseLabel('Ohne Kundenkonto'),
            ])
            ->recordActions([
                Action::make('forward')
                    ->label('An Partner weiterleiten')
                    ->icon(Heroicon::OutlinedPaperAirplane)
                    ->color('primary')
                    ->visible(fn (Inquiry $record): bool => $record->forward_consent && ! $record->forwarded_at)
                    ->schema([
                        TextInput::make('to')->label('E-Mail des Partners')->email()->required()
                            ->default(fn (): ?string => app(Site::class)->get('partner_email')),
                    ])
                    ->action(function (Inquiry $record, array $data): void {
                        $ok = PartnerForwarder::send($record, app(Site::class), $data['to']);
                        Notification::make()
                            ->title($ok ? 'Anfrage weitergeleitet' : 'Weiterleitung fehlgeschlagen – E-Mail-Einstellungen prüfen')
                            ->{$ok ? 'success' : 'danger'}()
                            ->send();
                    }),
                EditAction::make()->label('Öffnen'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInquiries::route('/'),
            'edit' => EditInquiry::route('/{record}/edit'),
        ];
    }
}
