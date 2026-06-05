<div>
    <x-filament-panels::page>
        <div class="space-y-6">
            {{-- Search Section --}}
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                <form wire:submit="search" class="flex items-end gap-4">
                    <div class="flex-1">
                        {{ $this->searchForm }}
                    </div>
                    <x-filament::button type="submit" icon="heroicon-o-magnifying-glass">
                        Search
                    </x-filament::button>
                </form>
            </div>

            {{-- Device Info + Edit --}}
            @if($this->device)
                <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-4 grid grid-cols-2 gap-4 text-sm md:grid-cols-4">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Device IMEI</span>
                            <p class="font-mono font-semibold">{{ $this->device->imei }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Name</span>
                            <p class="font-semibold">{{ $this->device->name ?? '—' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Device Type</span>
                            <p class="font-semibold">{{ $this->device->deviceType?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Status</span>
                            <x-filament::badge :color="$this->device->status->color()">
                                {{ $this->device->status->label() }}
                            </x-filament::badge>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    <form wire:submit="save">
                        {{ $this->editForm }}

                        <div class="mt-4 flex justify-end">
                            <x-filament::button type="submit" icon="heroicon-o-check">
                                Save GSM Details
                            </x-filament::button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </x-filament-panels::page>
</div>
