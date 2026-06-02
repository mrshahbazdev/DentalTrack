<x-filament-panels::page>
    <div class="space-y-6" wire:poll.15s>
        {{-- Date Filters --}}
        <div class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('app.common.from') }}</label>
                <x-filament::input.wrapper>
                    <x-filament::input type="date" wire:model.live="dateFrom" />
                </x-filament::input.wrapper>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('app.common.to') }}</label>
                <x-filament::input.wrapper>
                    <x-filament::input type="date" wire:model.live="dateTo" />
                </x-filament::input.wrapper>
            </div>
        </div>

        {{-- Summary Stats --}}
        @php $stats = $this->getSummaryStats(); @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_orders'] }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ __('app.analytics.total_orders') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $stats['total_reworks'] }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ __('app.quality.total_reworks') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold {{ $stats['rework_rate'] > 10 ? 'text-red-600' : ($stats['rework_rate'] > 5 ? 'text-yellow-600' : 'text-green-600') }}">
                    {{ $stats['rework_rate'] }}%
                </div>
                <div class="text-xs text-gray-500 mt-1">{{ __('app.quality.rework_rate') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ __('app.status.pending') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['in_rework'] }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ __('app.rework.in_rework') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['resolved'] }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ __('app.rework.resolved') }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Cause Breakdown --}}
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
                <div class="px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white">
                    <h3 class="text-sm font-semibold">{{ __('app.qc.root_cause_breakdown') }}</h3>
                </div>
                <div class="p-4 space-y-3">
                    @php $causes = $this->getCauseBreakdown(); @endphp
                    @forelse ($causes as $cause)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $cause['label'] }}</span>
                                <span class="text-gray-500">{{ $cause['count'] }} ({{ $cause['pct'] }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ min(100, $cause['pct']) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">{{ __('app.qc.no_rework_events') }}</p>
                    @endforelse
                </div>
            </div>

            {{-- Technician Quality --}}
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('app.qc.technician_quality') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="px-4 py-2 text-left">{{ __('app.common.technician') }}</th>
                                <th class="px-4 py-2 text-center">{{ __('app.qc.reworks') }}</th>
                                <th class="px-4 py-2 text-center">{{ __('app.qc.total_steps') }}</th>
                                <th class="px-4 py-2 text-center">{{ __('app.qc.rework_pct') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @php $techQuality = $this->getTechnicianQuality(); @endphp
                            @forelse ($techQuality as $tech)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">{{ $tech['user'] }}</td>
                                    <td class="px-4 py-2 text-center text-red-600 font-medium">{{ $tech['rework_count'] }}</td>
                                    <td class="px-4 py-2 text-center text-gray-500">{{ $tech['total_steps'] }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <span class="{{ $tech['rework_rate'] > 10 ? 'text-red-600' : ($tech['rework_rate'] > 5 ? 'text-yellow-600' : 'text-green-600') }} font-semibold">
                                            {{ $tech['rework_rate'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-400">{{ __('app.qc.no_technician_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Rework Events --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('app.qc.recent_rework_events') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">{{ __('app.common.order') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.qc.step') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.qc.cause') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.common.status') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.qc.flagged_by') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.qc.when') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @php $recentReworks = $this->getRecentReworks(); @endphp
                        @forelse ($recentReworks as $rework)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">#{{ $rework['order_id'] }}</td>
                                <td class="px-4 py-2 text-gray-500">{{ $rework['step'] }}</td>
                                <td class="px-4 py-2">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-red-100 text-red-700">
                                        {{ $rework['cause'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium',
                                        'bg-yellow-100 text-yellow-700' => $rework['status'] === __('app.rework.pending'),
                                        'bg-blue-100 text-blue-700' => $rework['status'] === __('app.rework.in_rework'),
                                        'bg-green-100 text-green-700' => $rework['status'] === __('app.rework.resolved'),
                                    ])>
                                        {{ $rework['status'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-gray-500">{{ $rework['flagged_by'] }}</td>
                                <td class="px-4 py-2 text-gray-400 text-xs">{{ $rework['created'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-400">{{ __('app.qc.no_rework_events') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
