<x-filament-panels::page>
    <div class="space-y-6" wire:poll.10s>
        {{-- Filters --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
            </div>
        </div>

        {{-- Station Statistics Table --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
            <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-sm font-semibold">{{ __('app.monitoring.avg_time_per_station') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">{{ __('app.monitoring.station_name') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('app.monitoring.station_type') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('app.common.lab') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('app.monitoring.avg_duration') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('app.monitoring.min_duration') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('app.monitoring.max_duration') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('app.monitoring.total_events') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('app.monitoring.active_orders') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($this->getStationStats() as $station)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $station['name'] }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $station['type'] }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $station['lab'] }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-blue-600">{{ $station['avg_duration'] }}</td>
                                <td class="px-4 py-3 text-center text-green-600">{{ $station['min_duration'] }}</td>
                                <td class="px-4 py-3 text-center text-red-600">{{ $station['max_duration'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-400">
                                        {{ $station['total_events'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($station['active_orders'] > 0)
                                        <span class="inline-flex items-center rounded-full bg-yellow-50 dark:bg-yellow-900/30 px-2.5 py-0.5 text-xs font-medium text-yellow-700 dark:text-yellow-400">
                                            {{ $station['active_orders'] }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                    {{ __('app.monitoring.no_monitoring_data') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('app.monitoring.recent_activity') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">{{ __('app.common.order') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.common.workstation') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.common.technician') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.monitoring.event_type') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.monitoring.duration') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.monitoring.time') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($this->getRecentActivity() as $event)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">#{{ $event['order_id'] }}</td>
                                <td class="px-4 py-2 text-gray-500 dark:text-gray-400">{{ $event['workstation'] }}</td>
                                <td class="px-4 py-2 text-gray-500 dark:text-gray-400">{{ $event['technician'] }}</td>
                                <td class="px-4 py-2">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $event['event_type'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $event['duration'] }}</td>
                                <td class="px-4 py-2 text-gray-400 text-xs">{{ $event['time'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                    {{ __('app.monitoring.no_recent_activity') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
