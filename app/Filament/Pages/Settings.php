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
use Filament\Forms\Components\Toggle;
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
        'email', 'notify_email', 'partner_name', 'partner_email', 'phone', 'address', 'linkedin',
        'hero_kicker', 'hero_title', 'hero_text', 'stats',
        'footer_text', 'imprint', 'privacy',
        'app_name', 'app_url', 'app_text', 'app_image',
        'tagline_en', 'meta_description_en', 'hero_kicker_en', 'hero_title_en', 'hero_text_en',
        'footer_text_en', 'app_text_en', 'imprint_en', 'privacy_en',
        'accounts_enabled', 'contact_requires_account',
        'consent_title', 'consent_text', 'consent_title_en', 'consent_text_en',
        'consent_stats_name', 'consent_stats_snippet', 'consent_version',
        'stats_enabled', 'stats_require_consent',
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
                                Section::make('Partner für Prüfanlagen')
                                    ->description('Bei Anfragen zum Thema „Shaker-Prüfstand“ können Kunden der Weiterleitung an den Partner zustimmen. Mit E-Mail-Adresse geht die Anfrage dann automatisch an den Partner, sonst leiten Sie sie im Admin weiter.')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('partner_name')->label('Name des Partners')->placeholder('RMS Dynamic Test Systems'),
                                        TextInput::make('partner_email')->label('E-Mail für Weiterleitungen')->email()
                                            ->helperText('Leer = keine automatische Weiterleitung.'),
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
                                                TextInput::make('suffix_en')->label('Zusatz (EN)')->maxLength(20),
                                                TextInput::make('label_en')->label('Beschriftung (EN)')->maxLength(60)->columnSpan(2),
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
                        Tab::make('English')
                            ->icon(Heroicon::OutlinedLanguage)
                            ->schema([
                                Section::make('Englische Website (/en)')
                                    ->description('Leere Felder: Solange der deutsche Text unverändert ist, erscheint ein mitgelieferter englischer Standardtext – sonst der deutsche Text.')
                                    ->schema([
                                        TextInput::make('tagline_en')->label('Slogan (EN)'),
                                        Textarea::make('meta_description_en')->label('Beschreibung für Suchmaschinen (EN)')->rows(2)->maxLength(300),
                                        TextInput::make('hero_kicker_en')->label('Überzeile Startseite (EN)'),
                                        TextInput::make('hero_title_en')->label('Überschrift Startseite (EN)')->maxLength(120),
                                        Textarea::make('hero_text_en')->label('Einleitungstext Startseite (EN)')->rows(3),
                                        Textarea::make('app_text_en')->label('App-Kurzbeschreibung (EN)')->rows(2),
                                        Textarea::make('footer_text_en')->label('Text im Seitenfuß (EN)')->rows(2),
                                        Textarea::make('imprint_en')->label('Legal notice / Impressum (EN, optional)')->rows(8),
                                        Textarea::make('privacy_en')->label('Privacy policy (EN, optional)')->rows(8),
                                    ]),
                            ]),
                        Tab::make('Kundenkonto')
                            ->icon(Heroicon::OutlinedUserCircle)
                            ->schema([
                                Section::make('Kundenkonten auf der Website')
                                    ->description('Kunden können sich unter /konto registrieren, Anfragen stellen und deren Stand sehen. Antworten tragen Sie in der Anfrage unter „Antwort an den Kunden“ ein.')
                                    ->schema([
                                        Toggle::make('accounts_enabled')->label('Kundenkonten anbieten')->default(true),
                                        Toggle::make('contact_requires_account')
                                            ->label('Anfragen nur mit Kundenkonto')
                                            ->helperText('Aus: Jeder kann das Anfrageformular nutzen, angemeldete Kunden bekommen ihre Daten vorausgefüllt. Ein: Das Formular erscheint erst nach der Anmeldung.'),
                                    ]),
                            ]),
                        Tab::make('Cookies')
                            ->icon(Heroicon::OutlinedShieldCheck)
                            ->schema([
                                Section::make('Hinweis zur Einwilligung')
                                    ->description('Der Hinweis erscheint beim ersten Besuch, bis eine Auswahl gespeichert ist. Besucher können ihre Auswahl im Seitenfuß unter „Cookie-Einstellungen“ ändern.')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('consent_title')->label('Überschrift'),
                                        TextInput::make('consent_title_en')->label('Überschrift (EN)'),
                                        Textarea::make('consent_text')->label('Text')->rows(4),
                                        Textarea::make('consent_text_en')->label('Text (EN)')->rows(4),
                                        TextInput::make('consent_version')
                                            ->label('Version der Einwilligung')
                                            ->numeric()->minValue(1)->default(1)
                                            ->helperText('Erhöhen, wenn neue Dienste dazukommen – dann werden alle Besucher erneut gefragt.'),
                                    ]),
                                Section::make('Eigene Besucherstatistik')
                                    ->description('Zählt Seitenaufrufe auf Ihrem Server, ohne Cookies und ohne IP-Adressen zu speichern. Auswertung unter Website → Besucher. Ihre eigenen Besuche (angemeldet im Admin) werden nicht gezählt.')
                                    ->schema([
                                        Toggle::make('stats_enabled')->label('Besucher zählen')->default(true),
                                        Toggle::make('stats_require_consent')
                                            ->label('Nur mit Einwilligung zählen')
                                            ->helperText('Aus: Zählung ohne Cookies und ohne Personenbezug, ohne Nachfrage. Ein: Nur Besucher, die im Cookie-Hinweis „Statistik“ zustimmen, werden gezählt – die Zahlen fallen dann deutlich niedriger aus.'),
                                    ]),
                                Section::make('Externer Statistikdienst (optional)')
                                    ->description('Code eines Statistikdienstes, z. B. Matomo. Er wird erst geladen, nachdem ein Besucher „Statistik“ zugestimmt hat. Ohne Code erscheint die Kategorie nicht.')
                                    ->schema([
                                        TextInput::make('consent_stats_name')->label('Name des Dienstes')->placeholder('z. B. Matomo'),
                                        Textarea::make('consent_stats_snippet')
                                            ->label('Einbettungscode (HTML/JavaScript)')
                                            ->rows(8)
                                            ->extraInputAttributes(['style' => 'font-family: ui-monospace, monospace; font-size: 13px']),
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
