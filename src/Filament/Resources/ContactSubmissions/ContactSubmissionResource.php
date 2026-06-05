<?php

namespace TrackAnyDevice\Admin\Filament\Resources\ContactSubmissions;

use TrackAnyDevice\Admin\Filament\Resources\ContactSubmissions\Pages\EditContactSubmission;
use TrackAnyDevice\Admin\Filament\Resources\ContactSubmissions\Pages\ListContactSubmissions;
use TrackAnyDevice\Core\Models\ContactSubmission;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use TrackAnyDevice\Admin\Concerns\HasDepartmentAccess;
use TrackAnyDevice\Core\Enums\StaffDepartment;

class ContactSubmissionResource extends Resource
{
    use HasDepartmentAccess;
    protected static ?string $model = ContactSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static string|\UnitEnum|null $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Contact Inbox';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Submission')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->required()->columnSpan(1),
                    TextInput::make('email')->email()->required()->columnSpan(1),
                    TextInput::make('phone')->columnSpan(1),
                    TextInput::make('subject')->columnSpan(1),
                    Textarea::make('message')->rows(6)->required()->columnSpanFull(),
                ]),
            Section::make('Reply Tracking')
                ->columns(2)
                ->schema([
                    DateTimePicker::make('replied_at')->label('Replied at')->columnSpan(1),
                    Textarea::make('reply_notes')->rows(3)->columnSpanFull(),
                ]),
            Section::make('Metadata')
                ->collapsed()
                ->columns(2)
                ->schema([
                    TextInput::make('ip_address')->disabled()->columnSpan(1),
                    TextInput::make('user_agent')->disabled()->columnSpan(1),
                    TextInput::make('created_at')->disabled()->columnSpan(1),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('replied')
                    ->label('Status')
                    ->state(fn (ContactSubmission $r) => $r->replied_at !== null)
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedCheckCircle)
                    ->falseIcon(Heroicon::OutlinedClock)
                    ->trueColor('success')
                    ->falseColor('warning'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable()->copyable(),
                TextColumn::make('subject')->limit(40)->placeholder('—'),
                TextColumn::make('message')->limit(60)->placeholder('—')->color('gray'),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('replied_at')->dateTime('d M Y H:i')->placeholder('—')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('replied_at')
                    ->label('Replied')
                    ->placeholder('All')
                    ->trueLabel('Replied')
                    ->falseLabel('Pending')
                    ->queries(
                        true: fn ($q) => $q->whereNotNull('replied_at'),
                        false: fn ($q) => $q->whereNull('replied_at'),
                    ),
            ])
            ->recordActions([
                Action::make('markReplied')
                    ->label('Mark replied')
                    ->icon(Heroicon::OutlinedCheck)
                    ->visible(fn (ContactSubmission $r) => $r->replied_at === null)
                    ->action(fn (ContactSubmission $r) => $r->forceFill([
                        'replied_at' => now(),
                        'replied_by' => auth()->id(),
                    ])->save()),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    protected static function getAllowedDepartments(): array
    {
        return [StaffDepartment::Marketing];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactSubmissions::route('/'),
            'edit' => EditContactSubmission::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ContactSubmission::whereNull('replied_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
