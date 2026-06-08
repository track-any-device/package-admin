<?php

namespace TrackAnyDevice\Admin\Filament\Resources\Signals\Pages;

use TrackAnyDevice\Core\Enums\SignalEventType;
use TrackAnyDevice\Admin\Filament\Resources\Signals\SignalResource;
use TrackAnyDevice\Core\Models\Device;
use TrackAnyDevice\Core\Services\SignalService;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;

/**
 * Polished signal stream view backed by InfluxDB.
 *
 * Signals are time-series points (not Eloquent rows) so we render a custom
 * table with Livewire-bound filters: device IMEI / name substring, multi-
 * select event types, row limit. The Blade view uses wire:poll for live
 * refresh when auto-refresh is on.
 */
class ListSignals extends Page
{
    protected static string $resource = SignalResource::class;

    protected string $view = 'tad-admin::filament.resources.signals.list';

    public string $deviceFilter = '';

    /** @var array<int, string> */
    public array $eventFilter = [];

    public int $limit = 100;

    public bool $autoRefresh = true;

    /** @return array<int, array<string, mixed>> */
    public function getSignalsProperty(): array
    {
        $service = app(SignalService::class);

        $devices = Device::query()
            ->select(['id', 'imei', 'name', 'tenant_id', 'last_signal_at'])
            ->orderByDesc('last_signal_at')
            ->when($this->deviceFilter !== '', function ($q): void {
                $needle = '%'.trim($this->deviceFilter).'%';
                $q->where(fn ($q) => $q->where('imei', 'like', $needle)->orWhere('name', 'like', $needle));
            })
            ->limit(50)
            ->get();

        return $devices->flatMap(function (Device $device) use ($service): Collection {
            return $service
                ->latestForDevice($device->id, $this->limit)
                ->map(fn ($signal) => $signal->toArray() + [
                    'device_imei' => $device->imei,
                    'device_name' => $device->name,
                    'device_id' => $device->id,
                ]);
        })
            ->when(! empty($this->eventFilter), fn ($c) => $c->whereIn('event_type', $this->eventFilter))
            ->sortByDesc('server_time')
            ->take($this->limit)
            ->values()
            ->all();
    }

    /** @return array<int, array{value:string, label:string, color:string}> */
    public function getEventTypeOptions(): array
    {
        return collect(SignalEventType::cases())
            ->map(fn (SignalEventType $type) => [
                'value' => $type->value,
                'label' => $type->label(),
                'color' => $type->color(),
            ])
            ->all();
    }

    public function getViewData(): array
    {
        return [
            'signals' => $this->signals,
            'eventTypeOptions' => $this->getEventTypeOptions(),
            'isInfluxConnected' => app(SignalService::class)->enabled(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clearFilters')
                ->label('Clear filters')
                ->color('gray')
                ->action(function () {
                    $this->deviceFilter = '';
                    $this->eventFilter = [];
                    $this->limit = 100;
                })
                ->visible(fn () => $this->deviceFilter !== '' || ! empty($this->eventFilter)),

            Action::make('toggleAutoRefresh')
                ->label(fn () => $this->autoRefresh ? 'Pause auto-refresh' : 'Resume auto-refresh')
                ->icon(fn () => $this->autoRefresh ? 'heroicon-o-pause' : 'heroicon-o-play')
                ->color('gray')
                ->action(fn () => $this->autoRefresh = ! $this->autoRefresh),
        ];
    }
}
