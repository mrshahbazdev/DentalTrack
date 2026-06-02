<x-filament-panels::page>
    <div class="space-y-6" wire:poll.15s>
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

        {{-- Company Cards Side by Side --}}
        @php $companies = $this->getCompanyKpis(); @endphp
        <div class="grid grid-cols-1 lg:grid-cols-{{ count($companies) }} gap-6">
            @foreach ($companies as $company)
                <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                        <h3 class="text-lg font-bold">{{ $company['name'] }}</h3>
                        <p class="text-blue-100 text-sm">{{ $company['technicians'] }} {{ __('app.comparison.technicians') }} · {{ $company['workstations'] }} {{ __('app.comparison.workstations') }}</p>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- KPI Grid --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $company['total_orders'] }}</div>
                                <div class="text-xs text-gray-500">{{ __('app.analytics.total_orders') }}</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">{{ $company['completed'] }}</div>
                                <div class="text-xs text-gray-500">{{ __('app.analytics.completed') }}</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="text-2xl font-bold text-red-600">{{ $company['overdue'] }}</div>
                                <div class="text-xs text-gray-500">{{ __('app.analytics.overdue') }}</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">{{ $company['orders_per_day'] }}</div>
                                <div class="text-xs text-gray-500">{{ __('app.performance.orders_per_day') }}</div>
                            </div>
                        </div>

                        {{-- Metrics --}}
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('app.comparison.on_time_delivery') }}</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $company['on_time_pct'] >= 80 ? 'bg-green-500' : ($company['on_time_pct'] >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                            style="width: {{ min(100, $company['on_time_pct']) }}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold {{ $company['on_time_pct'] >= 80 ? 'text-green-600' : ($company['on_time_pct'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $company['on_time_pct'] }}%
                                    </span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('app.analytics.completion_rate') }}</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-blue-500"
                                            style="width: {{ min(100, $company['completion_rate']) }}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-blue-600">{{ $company['completion_rate'] }}%</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('app.comparison.avg_step_time') }}</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $company['avg_step_minutes'] }} min</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
