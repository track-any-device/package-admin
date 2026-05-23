<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Users\Pages;

use TrackAnyDevice\Core\Enums\Role;
use TrackAnyDevice\Admin\Filament\Resources\Users\UserResource;
use TrackAnyDevice\Core\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Standard create
            CreateAction::make(),

            // Invite — creates user with random password then emails a set-password link
            Action::make('invite')
                ->label('Invite User')
                ->icon('heroicon-o-envelope')
                ->color('info')
                ->form([
                    TextInput::make('name')->required()->maxLength(150),
                    TextInput::make('email')->email()->required()->unique(User::class),
                    Select::make('role')
                        ->options(collect(Role::cases())->mapWithKeys(
                            fn (Role $r) => [$r->value => $r->label()]
                        )->all())
                        ->required()
                        ->native(false)
                        ->default(Role::Supervisor->value),
                ])
                ->action(function (array $data) {
                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'role' => $data['role'],
                        'password' => bcrypt(Str::random(32)),
                        'email_verified_at' => now(),
                    ]);

                    $status = Password::sendResetLink(['email' => $user->email]);

                    if ($status === Password::ResetLinkSent) {
                        Notification::make()
                            ->title("Invitation sent to {$user->email}")
                            ->body('They will receive an email with a link to set their password.')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('User created but email could not be sent')
                            ->body("Ask {$user->email} to use 'Forgot password' to set their password.")
                            ->warning()
                            ->send();
                    }
                }),
        ];
    }
}
