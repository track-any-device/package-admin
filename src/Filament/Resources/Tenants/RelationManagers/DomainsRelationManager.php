<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Tenants\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DomainsRelationManager extends RelationManager
{
    protected static string $relationship = 'domains';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('domain')
                ->label('Hostname')
                ->required()
                ->maxLength(253)
                ->placeholder('e.g. acme.dev-fleet-tracking.code-fellow.com')
                ->helperText('Full hostname (no https://).')
                ->unique(table: 'domains', column: 'domain', ignoreRecord: true)
                ->columnSpanFull(),

            Toggle::make('is_primary')
                ->label('Primary Domain')
                ->helperText('The primary domain is used for redirect links and portal URLs.')
                ->default(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('domain')
                    ->copyable()
                    ->searchable()
                    ->url(fn ($record) => 'https://'.$record->domain, true),

                IconColumn::make('is_primary')
                    ->label('Primary')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('warning'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->label('Added'),
            ])
            ->headerActions([
                CreateAction::make()->label('Add Domain'),
            ])
            ->recordActions([
                Action::make('set_primary')
                    ->label('Set Primary')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->visible(fn ($record) => ! $record->is_primary)
                    ->action(function ($record) {
                        $record->getOwnerRecord()->domains()->update(['is_primary' => false]);
                        $record->update(['is_primary' => true]);
                        Notification::make()->title('Primary domain updated')->success()->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
