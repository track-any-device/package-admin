@php
    use Filament\Support\Enums\MaxWidth;
    use Filament\Support\Facades\FilamentIcon;
@endphp

<div>
    <x-filament-panels::page>
        <div class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                <div id="device-logs" class="space-y-2 font-mono text-sm">
                    <div class="text-gray-500 dark:text-gray-400">
                        Waiting for device log entries...
                    </div>
                </div>
            </div>
        </div>
    </x-filament-panels::page>
</div>
