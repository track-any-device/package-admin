<?php

namespace TrackAnyDevice\Admin\Filament\Resources\PolicyVersions;

use TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\Pages\CreatePolicyVersion;
use TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\Pages\EditPolicyVersion;
use TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\Pages\ListPolicyVersions;
use TrackAnyDevice\Admin\Filament\Resources\PolicyVersions\Pages\ViewPolicyVersion;
use TrackAnyDevice\Core\Models\PolicyVersion;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class PolicyVersionResource extends Resource
{
    use HasDepartmentAccess;

    protected static ?string $model = PolicyVersion::class;

    protected static ?string $slug = 'policy-versions';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Policy Versions';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Policy details')
                ->columns(2)
                ->schema([
                    Select::make('type')
                        ->options([
                            PolicyVersion::TYPE_TERMS => 'Terms of Service',
                            PolicyVersion::TYPE_PRIVACY => 'Privacy Policy',
                            PolicyVersion::TYPE_COOKIE => 'Cookie Policy',
                        ])
                        ->required()
                        ->native(false),

                    TextInput::make('version')
                        ->required()
                        ->regex('/^[0-9]+(\.[0-9]+){0,2}$/')
                        ->helperText('Semver-style identifier, e.g. 1.0.0 or 1.1.')
                        ->maxLength(20),

                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    DatePicker::make('effective_from')
                        ->required()
                        ->default(today()),

                    Toggle::make('is_current')
                        ->label('Mark as the current version of this type')
                        ->helperText('Saving with this on automatically deactivates the previous current version.')
                        ->default(true),
                ]),

            Section::make('Content')
                ->schema([
                    Textarea::make('content')
                        ->label('Body (Markdown)')
                        ->rows(20)
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        PolicyVersion::TYPE_TERMS => 'Terms',
                        PolicyVersion::TYPE_PRIVACY => 'Privacy',
                        PolicyVersion::TYPE_COOKIE => 'Cookie',
                        default => $state,
                    })
                    ->color('info')
                    ->sortable(),

                TextColumn::make('version')
                    ->sortable(),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('effective_from')
                    ->date()
                    ->sortable(),

                IconColumn::make('is_current')
                    ->boolean()
                    ->label('Current'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        PolicyVersion::TYPE_TERMS => 'Terms',
                        PolicyVersion::TYPE_PRIVACY => 'Privacy',
                        PolicyVersion::TYPE_COOKIE => 'Cookie',
                    ]),
            ])
            ->recordActions([
                Action::make('make_current')
                    ->label('Make Current')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (PolicyVersion $record) => ! $record->is_current)
                    ->requiresConfirmation()
                    ->modalHeading('Promote this version to current?')
                    ->modalDescription(fn (PolicyVersion $record) => "The current {$record->type} version will be deactivated.")
                    ->action(function (PolicyVersion $record) {
                        $record->setCurrent();
                        Notification::make()
                            ->title("Version {$record->version} is now current")
                            ->success()
                            ->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('effective_from', 'desc');
    }

    /** @return list<StaffDepartment> */
    public static function getAllowedDepartments(): array
    {
        return [StaffDepartment::CoreTeam];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPolicyVersions::route('/'),
            'create' => CreatePolicyVersion::route('/create'),
            'view' => ViewPolicyVersion::route('/{record}'),
            'edit' => EditPolicyVersion::route('/{record}/edit'),
        ];
    }
}
