<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\Site;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class Settings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 80;

    protected static ?string $navigationLabel = 'Website & Logo';

    protected static ?string $title = 'Website-Einstellungen';

    protected static ?string $slug = 'einstellungen';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** Diese Schlüssel werden gespeichert (alles andere wird ignoriert). */
    protected const KEYS = [
        'company_name', 'tagline', 'meta_description', 'logo', 'logo_dark', 'favicon',
        'email', 'notify_email', 'phone', 'address', 'linkedin',
        'hero_kicker', 'hero_title', 'hero_text', 'stats',
        'footer_text', 'imprint', 'privacy',
        'app_name', 'app_url', 'app_text', 'app_image',
    ];

    public function mount(): void
    {
        $values = app(Site::class)->all();

        $this->form->fill(array_intersect_key($values, array_flip(self::KEYS)));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('settings')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Marke & Logo')
                            ->icon(Heroicon::OutlinedSparkles)
                            ->schema([
                                Section::make('Marke')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('company_name')->label('Firmen-/Markenname')->required(),
                                        TextInput::make('tagline')->label('Slogan'),
                                        Textarea::make('meta_description')->label('Beschreibung für Suchmaschinen')->rows(2)->maxLength(300)->columnSpanFull(),
                                    ]),
                                Section::make('Logo')
                                    ->description('Ohne Upload wird das mitgelieferte vib-hub-Logo verwendet. Empfohlen: SVG oder PNG mit transparentem Hintergrund.')
                                    ->columns(3)
                                    ->schema([
                                        FileUpload::make('logo')
                                            ->label('Logo')
                                            ->helperText('Für helle Hintergründe – Website und Admin.')
                                            ->disk('public')
                                            ->directory('branding')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/webp', 'image/jpeg'])
                                            ->maxSize(2048),
                                        FileUpload::make('logo_dark')
                                            ->label('Logo für dunkle Hintergründe')
                                            ->helperText('Optional – z. B. für den Admin im Dunkelmodus.')
                                            ->disk('public')
                                            ->directory('branding')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/webp', 'image/jpeg'])
                                            ->maxSize(2048),
                                        FileUpload::make('favicon')
                                            ->label('Favicon')
                                            ->helperText('Quadratisch, mind. 64×64 px.')
                                            ->disk('public')
                                            ->directory('branding')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/x-icon', 'image/vnd.microsoft.icon'])
                                            ->maxSize(512),
                                    ]),
                            ]),
                        Tab::make('Kontakt')
                            ->icon(Heroicon::OutlinedEnvelope)
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('email')->label('E-Mail (öffentlich)')->email(),
                                        TextInput::make('notify_email')->label('Benachrichtigung bei neuen Anfragen an')->email()
                                            ->helperText('Leer = keine E-Mail, Anfragen erscheinen nur im Admin.'),
                                        TextInput::make('phone')->label('Telefon')->tel(),
                                        TextInput::make('linkedin')->label('LinkedIn-URL')->url(),
                                        Textarea::make('address')->label('Anschrift')->rows(3)->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Startseite')
                            ->icon(Heroicon::OutlinedHome)
                            ->schema([
                                Section::make('Kopfbereich')
                                    ->schema([
                                        TextInput::make('hero_kicker')->label('Überzeile'),
                                        TextInput::make('hero_title')->label('Überschrift')->required()->maxLength(120),
                                        Textarea::make('hero_text')->label('Einleitungstext')->rows(3),
                                    ]),
                                Section::make('Kennzahlen')
                                    ->schema([
                                        Repeater::make('stats')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('value')->label('Zahl')->required()->maxLength(12),
                                                TextInput::make('suffix')->label('Zusatz')->maxLength(20),
                                                TextInput::make('label')->label('Beschriftung')->required()->maxLength(60),
                                            ])
                                            ->columns(3)
                                            ->maxItems(4)
                                            ->addActionLabel('Kennzahl hinzufügen'),
                                    ]),
                                Textarea::make('footer_text')->label('Text im Seitenfuß')->rows(2),
                            ]),
                        Tab::make('App')
                            ->icon(Heroicon::OutlinedDevicePhoneMobile)
                            ->schema([
                                Section::make('PSD Profil Rechner')
                                    ->description('Die App wird auf Startseite, Rechnerseite, im Menü und auf /app beworben. Ohne Store-Link wird sie überall ausgeblendet.')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('app_name')->label('App-Name'),
                                        TextInput::make('app_url')->label('Link zum Google Play Store')->url(),
                                        Textarea::make('app_text')->label('Kurzbeschreibung')->rows(3)->columnSpanFull(),
                                        FileUpload::make('app_image')
                                            ->label('Screenshot der App')
                                            ->helperText('Hochformat, z. B. 1080×2340 px. Ohne Bild zeigt die Website eine gezeichnete Illustration.')
                                            ->disk('public')
                                            ->directory('branding')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/png', 'image/webp', 'image/jpeg'])
                                            ->maxSize(4096),
                                    ]),
                            ]),
                        Tab::make('Rechtliches')
                            ->icon(Heroicon::OutlinedScale)
                            ->schema([
                                Textarea::make('imprint')->label('Impressum')->rows(14)
                                    ->helperText('Einfacher Text; Leerzeilen trennen Absätze.'),
                                Textarea::make('privacy')->label('Datenschutzerklärung')->rows(18),
                            ]),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label('Speichern')
                            ->submit('save')
                            ->keyBindings(['mod+s']),
                    ])->key('form-actions'),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::setMany(array_intersect_key($data, array_flip(self::KEYS)));

        Notification::make()
            ->success()
            ->title('Einstellungen gespeichert')
            ->send();

        // Seite neu laden, damit z. B. ein neues Logo sofort im Admin erscheint.
        $this->redirect(static::getUrl());
    }
}
