<?php

namespace TrackAnyDevice\Admin\Filament\Pages;

use TrackAnyDevice\Core\Enums\StaffDepartment;
use TrackAnyDevice\Core\Models\Device;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class DeviceSetup extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static string|\UnitEnum|null $navigationGroup = 'Workshop';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Device Setup';
    protected string $view = 'filament.pages.device-setup';

    public ?string $imei_search = null;
    public ?Device $device = null;

    public ?string $sim_number = null;
    public ?string $gsm_number = null;
    public ?int $gsm_network_id = null;
    public ?string $iccid = null;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user || ! method_exists($user, 'isWorkshop')) {
            return false;
        }

        return $user->isAdmin()
            || $user->hasDepartment(StaffDepartment::CoreTeam)
            || $user->isWorkshop();
    }

    public function searchForm(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('imei_search')
                    ->label('Device IMEI')
                    ->placeholder('Enter device IMEI to search')
                    ->required()
                    ->maxLength(20),
            ])
            ->statePath('');
    }

    public function editForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('GSM Configuration')
                    ->columns(2)
                    ->schema([
                        TextInput::make('sim_number')
                            ->label('GSM Number (Phone)')
                            ->maxLength(30),

                        TextInput::make('gsm_number')
                            ->label('GSM IMEI')
                            ->maxLength(20),

                        Select::make('gsm_network_id')
                            ->label('GSM Network')
                            ->relationship('gsmNetwork', 'name')
                            ->options(
                                \TrackAnyDevice\Core\Models\GsmNetwork::where('is_active', true)
                                    ->pluck('name', 'id')
                                    ->all()
                            )
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        TextInput::make('iccid')
                            ->label('ICCID')
                            ->maxLength(22),
                    ]),
            ])
            ->statePath('');
    }

    public function search(): void
    {
        $this->device = Device::where('imei', $this->imei_search)->first();

        if (! $this->device) {
            Notification::make()
                ->title('Device not found')
                ->body("No device with IMEI: {$this->imei_search}")
                ->danger()
                ->send();

            return;
        }

        $this->sim_number = $this->device->sim_number;
        $this->gsm_number = $this->device->gsm_number;
        $this->gsm_network_id = $this->device->gsm_network_id;
        $this->iccid = $this->device->iccid;
    }

    public function save(): void
    {
        if (! $this->device) {
            return;
        }

        $this->device->update([
            'sim_number' => $this->sim_number,
            'gsm_number' => $this->gsm_number,
            'gsm_network_id' => $this->gsm_network_id,
            'iccid' => $this->iccid,
        ]);

        Notification::make()
            ->title('Device updated')
            ->body("GSM details saved for IMEI: {$this->device->imei}")
            ->success()
            ->send();
    }

    protected function getForms(): array
    {
        return ['searchForm', 'editForm'];
    }
}
