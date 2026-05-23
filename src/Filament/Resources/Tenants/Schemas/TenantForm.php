<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Tenants\Schemas;

use TrackAnyDevice\Core\Models\TenantStatus;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identity')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(150)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (callable $set, ?string $state) => $set('slug', Str::slug($state ?? '')))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(80)
                            ->unique(ignoreRecord: true)
                            ->helperText('Used as the subdomain: {slug}.yourdomain.com. Lowercase, numbers and hyphens only.')
                            ->rules(['alpha_dash']),

                        TextInput::make('app_name')
                            ->label('Custom App Name')
                            ->maxLength(100)
                            ->placeholder('Leave blank to use the global APP_NAME')
                            ->helperText('Shown in the tenant portal header instead of the default app name.'),

                        Select::make('type')
                            ->label('Tenant Type')
                            ->options([
                                'portal' => 'Portal — standard fleet monitoring portal',
                            ])
                            ->default('portal')
                            ->required()
                            ->helperText('Controls the home page layout.'),

                        Select::make('interface_mode')
                            ->label('Interface Mode')
                            ->options([
                                'default' => 'Default — public landing + portal',
                                'no_org' => 'No Org — bounce visitors to central /login',
                            ])
                            ->default('default')
                            ->required()
                            ->helperText('"No Org" hides the tenant\'s landing page; the subdomain only serves authenticated users.'),

                        Select::make('status')
                            ->label('Status')
                            ->options(collect(TenantStatus::cases())->mapWithKeys(
                                fn (TenantStatus $s) => [$s->value => $s->label()]
                            )->all())
                            ->default(TenantStatus::Pending->value)
                            ->required()
                            ->helperText('Pending tenants show an "Awaiting Approval" page on their subdomain.')
                            ->columnSpanFull(),

                        DateTimePicker::make('approved_at')
                            ->label('Approved At')
                            ->seconds(false)
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Set automatically on approval')
                            ->columnSpanFull(),

                        Toggle::make('registration_enabled')
                            ->label('Allow Public Registration')
                            ->helperText('When enabled, visitors to this tenant\'s subdomain can self-register at /register and join as Tenant Users. When disabled, /register returns 404 on this subdomain.')
                            ->default(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Branding')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->directory('tenant-logos')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('PNG or SVG recommended, max 2 MB. Displayed in the portal header and the tenant store page.')
                            ->columnSpanFull(),

                        ColorPicker::make('primary_color')
                            ->label('Primary Colour')
                            ->helperText('Override the default brand colour for this tenant (optional).'),

                        Select::make('color_scheme')
                            ->label('Color Scheme')
                            ->options(
                                collect(config('color-schemes', []))
                                    ->mapWithKeys(fn (string $hex, string $key) => [
                                        $key => '<span class="inline-flex items-center gap-2">'
                                            .'<span style="background:'.$hex.'" class="inline-block h-3 w-3 rounded-full ring-1 ring-black/10"></span>'
                                            .ucfirst($key)
                                            .'</span>',
                                    ])
                                    ->all(),
                            )
                            ->default('default')
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->allowHtml()
                            ->helperText('Drives the portal accent colour palette. "Default" is the canonical Blue and the only scheme used by the central host.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Integrations')
                    ->columns(1)
                    ->schema([
                        TextInput::make('google_maps_api_key')
                            ->label('Google Maps API Key')
                            ->password()
                            ->revealable()
                            ->maxLength(100)
                            ->helperText('Optional. When set, the tenant\'s live map renders with Google Maps. Leave blank to keep the default OpenStreetMap base layer.'),
                    ]),

                Section::make('Metadata')
                    ->description('Store any extra structured information about this tenant (department, government, contact details, etc.).')
                    ->schema([
                        KeyValue::make('metadata')
                            ->label(false)
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->addActionLabel('Add field')
                            ->reorderable(),
                    ])
                    ->collapsed(),
            ]);
    }
}
