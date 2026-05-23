<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Tenants\RelationManagers;

use TrackAnyDevice\Core\Enums\Role;
use TrackAnyDevice\Core\Models\User;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Members';

    // ── Form ─────────────────────────────────────────────────────────────────

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(150)
                ->columnSpanFull(),

            TextInput::make('email')
                ->email()
                ->required()
                ->unique(table: 'users', column: 'email', ignoreRecord: true)
                ->maxLength(255),

            TextInput::make('password')
                ->password()
                ->revealable()
                ->minLength(8)
                ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $operation): bool => $operation === 'create')
                ->helperText('Leave blank when editing to keep the existing password.'),

            TextInput::make('primary_contact')
                ->label('Mobile / Contact')
                ->tel()
                ->nullable()
                ->maxLength(30),
        ]);
    }

    // ── Table ────────────────────────────────────────────────────────────────

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (User $record) => $record->email),

                TextColumn::make('role')
                    ->badge()
                    ->color(fn (Role|string $state): string => match ($state instanceof Role ? $state->value : $state) {
                        Role::Admin->value => 'danger',
                        Role::Supervisor->value => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (Role|string $state): string => $state instanceof Role
                        ? $state->label()
                        : (Role::tryFrom($state)?->label() ?? $state)),

                TextColumn::make('primary_contact')
                    ->label('Contact')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime('d M Y')
                    ->placeholder('Not verified')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                // Create a brand-new user and automatically attach to this tenant
                CreateAction::make()
                    ->label('Create Member')
                    ->mutateFormDataUsing(function (array $data): array {
                        // Members created through a tenant are always tenant_user.
                        // Admin/Supervisor/Staff are central staff and live outside
                        // any tenant scope, so they are not assignable here.
                        $data['role'] = Role::TenantUser->value;

                        return $data;
                    })
                    ->after(function (User $record, RelationManager $livewire): void {
                        // Ensure the pivot row exists (CreateAction on BelongsToMany
                        // handles this, but we guarantee idempotency here).
                        $livewire->getOwnerRecord()->users()->syncWithoutDetaching([$record->id]);
                    }),

                // Attach any existing platform user (admin, supervisor, staff,
                // tenant_user, or end-user) to this tenant. Central staff
                // already have implicit access via Role::isCentralStaff(), but
                // an explicit pivot row keeps them visible on the tenant's
                // member list. Users already attached to this tenant are
                // filtered out.
                AttachAction::make()
                    ->label('Add Existing User')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn ($query) => $query
                        ->whereDoesntHave('tenants', fn ($q) => $q->where('tenants.id', $this->getOwnerRecord()->id))
                    )
                    ->recordSelectSearchColumns(['name', 'email']),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading(fn (User $record) => "Edit {$record->name}"),

                Action::make('impersonate')
                    ->label('View as User')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (User $record) => route('filament.admin.resources.users.edit', $record))
                    ->openUrlInNewTab(),

                DetachAction::make()
                    ->label('Remove from Tenant')
                    ->modalHeading('Remove Member')
                    ->modalDescription('This removes the user from this tenant but does not delete their account.')
                    ->before(function (User $record, RelationManager $livewire): void {
                        Notification::make()
                            ->title("{$record->name} removed from tenant")
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
