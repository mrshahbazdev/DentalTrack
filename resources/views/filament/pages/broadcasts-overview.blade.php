<x-filament-panels::page>
    <div class="space-y-6" wire:poll.10s>
        {{-- Filters --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('app.common.from') }}</label>
                    <input type="date" wire:model.live="dateFrom"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('app.common.to') }}</label>
                    <input type="date" wire:model.live="dateTo"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('app.broadcasts.event_type') }}</label>
                    <select wire:model.live="eventTypeFilter"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white text-sm">
                        <option value="">{{ __('app.common.all_priorities') }}</option>
                        @foreach (\App\Enums\ScanEventType::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        @php $stats = $this->getSummaryStats(); @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 text-center">
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('app.broadcasts.total_broadcasts') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $stats['today'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('app.broadcasts.today') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 text-center">
                <div class="text-3xl font-bold text-green-600">{{ $stats['last_hour'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('app.broadcasts.last_hour') }}</div>
            </div>
        </div>

        {{-- Broadcasts Table --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
            <div class="px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white">
                <h3 class="text-sm font-semibold">{{ __('app.broadcasts.title') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">{{ __('app.broadcasts.order_id') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('app.broadcasts.channel') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('app.broadcasts.event_type') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('app.common.workstation') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('app.broadcasts.triggered_by') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('app.broadcasts.triggered_at') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('app.broadcasts.payload') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($this->getBroadcastEvents() as $event)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">#{{ $event['order_id'] }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 dark:bg-indigo-900/30 px-2.5 py-0.5 text-xs font-mono text-indigo-700 dark:text-indigo-400">
                                        {{ $event['channel'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $event['event_type'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $event['workstation'] }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $event['technician'] }}</td>
                                <td class="px-4 py-3 text-gray-400 text-xs">{{ $event['triggered_at'] }}</td>
                                <td class="px-4 py-3 text-gray-400 text-xs font-mono max-w-[200px] truncate">{{ $event['payload'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                    {{ __('app.broadcasts.no_broadcasts') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
