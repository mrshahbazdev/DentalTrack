<x-filament-panels::page>
    <div class="space-y-6" wire:poll.15s>
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
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('app.common.company') }}</label>
                    <select wire:model.live="selectedCompany"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white text-sm">
                        <option value="">{{ __('app.common.all_companies') }}</option>
                        @foreach (\App\Models\Company::all() as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        @php $stats = $this->getSummaryStats(); @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_orders'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('app.analytics.total_orders') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('app.analytics.completed') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['in_progress'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('app.analytics.in_progress') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $stats['overdue'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('app.analytics.overdue') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['on_time_pct'] }}%</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('app.analytics.on_time_rate') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold text-indigo-600">{{ $stats['completion_rate'] }}%</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('app.analytics.completion_rate') }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Bottleneck Analysis --}}
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('app.analytics.bottleneck_analysis') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                            <tr>
                                <th class="px-4 py-2 text-left">{{ __('app.analytics.station') }}</th>
                                <th class="px-4 py-2 text-left">{{ __('app.common.lab') }}</th>
                                <th class="px-4 py-2 text-center">{{ __('app.analytics.avg_min') }}</th>
                                <th class="px-4 py-2 text-center">{{ __('app.analytics.events') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($this->getBottleneckAnalysis() as $i => $station)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            @if ($i === 0)
                                                <span class="text-red-500 text-xs font-bold">{{ __('app.analytics.slowest') }}</span>
                                            @endif
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $station['workstation'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-400">{{ $station['lab'] }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <span class="{{ $station['avg_minutes'] > 60 ? 'text-red-600 font-bold' : 'text-gray-900 dark:text-white' }}">
                                            {{ $station['avg_minutes'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-center text-gray-500">{{ $station['event_count'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-400">{{ __('app.common.no_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Product Type Breakdown --}}
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('app.analytics.product_type_breakdown') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                            <tr>
                                <th class="px-4 py-2 text-left">{{ __('app.common.product_type') }}</th>
                                <th class="px-4 py-2 text-center">{{ __('app.analytics.completed') }}</th>
                                <th class="px-4 py-2 text-center">{{ __('app.analytics.avg_hours') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($this->getProductTypeBreakdown() as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">{{ $product['product_type'] }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:text-green-400">
                                            {{ $product['count'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-center text-gray-900 dark:text-white">{{ $product['avg_hours'] }}h</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-400">{{ __('app.common.no_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Throughput Trend --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">{{ __('app.analytics.daily_throughput') }}</h3>
            @php $throughput = collect($this->getThroughputByDay()); @endphp
            @if ($throughput->isNotEmpty())
                @php $maxCount = $throughput->max('count') ?: 1; @endphp
                <div class="flex items-end gap-1 h-40">
                    @foreach ($throughput as $day)
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <span class="text-xs text-gray-500">{{ $day['count'] }}</span>
                            <div class="w-full bg-blue-500 rounded-t"
                                style="height: {{ max(4, ($day['count'] / $maxCount) * 120) }}px"></div>
                            <span class="text-[10px] text-gray-400 rotate-[-45deg] origin-top-left whitespace-nowrap mt-1">{{ $day['day'] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-400 py-8">{{ __('app.common.no_data') }}</div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
