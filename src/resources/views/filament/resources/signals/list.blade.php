<div>
    <x-filament-panels::page>
        @if(! $isInfluxConnected)
            <div class="rounded-lg border border-warning-300 bg-warning-50 p-4 text-sm text-warning-700 dark:border-warning-600 dark:bg-warning-900/20 dark:text-warning-400">
                InfluxDB is not connected. Signal data is unavailable.
            </div>
        @else
            <div class="space-y-4">
                {{-- Filters --}}
                <div class="flex flex-wrap items-end gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="min-w-[200px] flex-1">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Device</label>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="deviceFilter"
                            placeholder="Search by IMEI or name..."
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                    </div>

                    <div class="min-w-[200px]">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Event Type</label>
                        <select
                            wire:model.live="eventFilter"
                            multiple
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            @foreach($eventTypeOptions as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-24">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Limit</label>
                        <input
                            type="number"
                            wire:model.live.debounce.300ms="limit"
                            min="10"
                            max="1000"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                    </div>
                </div>

                {{-- Signal Table --}}
                <div
                    @if($this->autoRefresh) wire:poll.5s @endif
                    class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700"
                >
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Time</th>
                                <th class="px-4 py-3">Device</th>
                                <th class="px-4 py-3">Event</th>
                                <th class="px-4 py-3">Lat</th>
                                <th class="px-4 py-3">Lon</th>
                                <th class="px-4 py-3">Speed</th>
                                <th class="px-4 py-3">Battery</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($signals as $signal)
                                <tr class="bg-white hover:bg-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800">
                                    <td class="whitespace-nowrap px-4 py-2 font-mono text-xs text-gray-500 dark:text-gray-400">
                                        {{ $signal['server_time'] ?? '—' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="font-mono text-xs">{{ $signal['device_imei'] ?? '' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $signal['device_name'] ?? '' }}</div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                            {{ match($signal['event_type'] ?? '', 'sos' => 'bg-danger-100 text-danger-700 dark:bg-danger-900/30 dark:text-danger-400', 'low_battery' => 'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400', 'geofence_exit' => 'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400', default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300') }}
                                        ">
                                            {{ $signal['event_type'] ?? 'location' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 font-mono text-xs">{{ isset($signal['lat']) ? number_format($signal['lat'], 6) : '—' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 font-mono text-xs">{{ isset($signal['lon']) ? number_format($signal['lon'], 6) : '—' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-xs">{{ isset($signal['speed']) ? number_format($signal['speed'], 1) . ' km/h' : '—' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-xs">{{ isset($signal['battery']) ? $signal['battery'] . '%' : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No signals found. Adjust filters or wait for incoming data.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="text-right text-xs text-gray-400 dark:text-gray-500">
                    {{ count($signals) }} signal(s) displayed
                    @if($this->autoRefresh)
                        &middot; Auto-refreshing every 5s
                    @endif
                </div>
            </div>
        @endif
    </x-filament-panels::page>
</div>
