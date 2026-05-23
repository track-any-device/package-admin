<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Countries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CountryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Country')->columns(2)->schema([
                TextInput::make('name')->required(),
                TextInput::make('iso_code')->label('ISO Code')->required()->maxLength(2)->unique(ignoreRecord: true),
                TextInput::make('country_code')->label('Dial Code')->placeholder('+92')->required()->maxLength(5),
                TextInput::make('timezone')->required()->placeholder('Asia/Karachi'),
            ]),

            Section::make('Currency')->columns(2)->schema([
                TextInput::make('currency')->required()->placeholder('Pakistani Rupee'),
                TextInput::make('currency_code')->required()->maxLength(3)->placeholder('PKR'),
                Select::make('code_prepend_or_postpend')
                    ->options(['prepend' => 'Prepend (PKR 1,234)', 'postpend' => 'Postpend (1,234 PKR)'])
                    ->default('prepend')
                    ->required(),
                TextInput::make('decimal_values')->numeric()->required()->default(2)->minValue(0)->maxValue(6),
                TextInput::make('thousands_separator')->required()->maxLength(1)->default(','),
                TextInput::make('hundreds_separator')->required()->maxLength(1)->default(','),
                TextInput::make('decimal_separator')->required()->maxLength(1)->default('.'),
                TextInput::make('conversion_rate')
                    ->numeric()
                    ->step(0.000001)
                    ->required()
                    ->default(1)
                    ->helperText('Multiplier from the default country\'s currency.'),
                TextInput::make('conversion_markup_percent')
                    ->numeric()
                    ->step(0.01)
                    ->default(30)
                    ->helperText('Extra percentage added to cover FX cost (e.g. 30 → 30%).')
                    ->suffix('%'),
            ]),

            Section::make('Flags & Comms')->columns(3)->schema([
                Toggle::make('is_default')->helperText('There can be only one default country.'),
                Toggle::make('is_fallback')->helperText('Observe-only fallback when no SMS gateway is set.'),
                Toggle::make('is_active')->default(true),
                Select::make('sms_gateway')
                    ->options(config('sms.gateways', []))
                    ->placeholder('— No SMS gateway (observe-only) —')
                    ->helperText('Users from this country can only sign in/up when a gateway is set.'),
            ]),
        ]);
    }
}
