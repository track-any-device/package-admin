<?php

namespace TrackAnyDevice\Admin\Filament\Resources\AssigneeTypes\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AssigneeTypeForm
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
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')->required()->maxLength(100)->unique(ignoreRecord: true),
                        Textarea::make('description')->rows(2)->columnSpanFull(),
                    ]),
                Section::make('Appearance')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('icon_path')->label('Icon (SVG or PNG)')->image()->directory('assignee-type-icons')->columnSpan(1),
                        ColorPicker::make('icon_color')->label('Fallback Color')->columnSpan(1),
                        TextInput::make('sort_order')->numeric()->default(0)->columnSpan(1),
                        Toggle::make('is_active')->default(true)->columnSpan(1),
                    ]),
            ]);
    }
}
