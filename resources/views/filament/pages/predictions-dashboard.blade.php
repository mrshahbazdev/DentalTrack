<x-filament-panels::page>
    <div class="space-y-6" wire:poll.15s>
        {{-- Accuracy Stats --}}
        @php $stats = $this->getAccuracyStats(); @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 text-center">
                <div class="text-3xl font-bold {{ $stats['avg_accuracy'] >= 80 ? 'text-green-600' : ($stats['avg_accuracy'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $stats['avg_accuracy'] }}%
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('app.predictions.overall_accuracy') }}</div>
                <div class="text-xs text-gray-400 mt-1">{{ $stats['total_predictions'] }} {{ __('app.predictions.predictions_count') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 text-center">
                <div class="text-3xl font-bold {{ $stats['recent_accuracy'] >= 80 ? 'text-green-600' : ($stats['recent_accuracy'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $stats['recent_accuracy'] }}%
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('app.predictions.last_7_days') }}</div>
            </div>
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
                <div class="text-sm font-semibold text-gray-900 dark:text-white mb-2">{{ __('app.predictions.model_versions') }}</div>
                @foreach ($stats['by_version'] as $version => $data)
                    <div class="flex justify-between text-sm py-1">
                        <span class="text-gray-600 dark:text-gray-400 font-mono">{{ $version }}</span>
                        <span class="font-medium">{{ $data['accuracy'] }}% <span class="text-gray-400">({{ $data['count'] }})</span></span>
                    </div>
                @endforeach
                @if (empty($stats['by_version']))
                    <div class="text-sm text-gray-400">{{ __('app.predictions.no_predictions_yet') }}</div>
                @endif
            </div>
        </div>

        {{-- Accuracy Trend --}}
        @php $trend = $this->getAccuracyTrend(); @endphp
        @if (!empty($trend))
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">{{ __('app.predictions.accuracy_trend') }}</h3>
                @php $maxAcc = 100; @endphp
                <div class="flex items-end gap-1 h-32">
                    @foreach ($trend as $day)
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <span class="text-[10px] text-gray-500">{{ $day['accuracy'] }}%</span>
                            <div class="w-full rounded-t {{ $day['accuracy'] >= 80 ? 'bg-green-500' : ($day['accuracy'] >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                style="height: {{ max(4, ($day['accuracy'] / $maxAcc) * 100) }}px"></div>
                            <span class="text-[9px] text-gray-400 whitespace-nowrap">{{ $day['day'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Smart Suggestions --}}
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
                <div class="px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white">
                    <h3 class="text-sm font-semibold">{{ __('app.predictions.smart_suggestions') }}</h3>
                </div>
                <div class="p-4 space-y-3">
                    @php $suggestions = $this->getSmartSuggestions(); @endphp
                    @forelse ($suggestions as $suggestion)
                        <div @class([
                            'p-3 rounded-lg border-l-4',
                            'border-red-500 bg-red-50 dark:bg-red-900/20' => $suggestion['priority'] === 'high',
                            'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20' => $suggestion['priority'] === 'medium',
                            'border-blue-500 bg-blue-50 dark:bg-blue-900/20' => $suggestion['priority'] === 'low',
                        ])>
                            <div class="flex items-start gap-2">
                                @if ($suggestion['type'] === 'bottleneck')
                                    <span class="text-red-500 mt-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                    </span>
                                @elseif ($suggestion['type'] === 'fast_technician')
                                    <span class="text-green-500 mt-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </span>
                                @else
                                    <span class="text-yellow-500 mt-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                @endif
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $suggestion['message'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">{{ __('app.predictions.no_suggestions') }}</p>
                    @endforelse
                </div>
            </div>

            {{-- Active Order ETAs --}}
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('app.predictions.active_order_etas') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="px-4 py-2 text-left">{{ __('app.common.order') }}</th>
                                <th class="px-4 py-2 text-left">{{ __('app.common.product') }}</th>
                                <th class="px-4 py-2 text-left">{{ __('app.common.eta') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @php $activeEtas = $this->getActiveOrderPredictions(); @endphp
                            @forelse ($activeEtas as $eta)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">#{{ $eta['id'] }}</td>
                                    <td class="px-4 py-2 text-gray-500">{{ $eta['product'] }}</td>
                                    <td class="px-4 py-2">
                                        @if ($eta['predicted_at'])
                                            <span class="text-blue-600 font-medium">{{ $eta['predicted_at'] }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-400">{{ __('app.predictions.no_active_predictions') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Predictions Table --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('app.predictions.recent_predictions') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">{{ __('app.common.order') }}</th>
                            <th class="px-4 py-2 text-center">{{ __('app.predictions.predicted') }}</th>
                            <th class="px-4 py-2 text-center">{{ __('app.predictions.actual') }}</th>
                            <th class="px-4 py-2 text-center">{{ __('app.predictions.accuracy') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.predictions.version') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('app.predictions.when') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @php $recentPreds = $this->getRecentPredictions(); @endphp
                        @forelse ($recentPreds as $pred)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">#{{ $pred['order_id'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $pred['predicted'] }} min</td>
                                <td class="px-4 py-2 text-center">
                                    @if ($pred['actual'] !== null)
                                        {{ $pred['actual'] }} min
                                    @else
                                        <span class="text-gray-400">{{ __('app.status.pending') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center">
                                    @if ($pred['accuracy'] !== null)
                                        <span @class([
                                            'font-semibold',
                                            'text-green-600' => $pred['accuracy'] >= 80,
                                            'text-yellow-600' => $pred['accuracy'] >= 60 && $pred['accuracy'] < 80,
                                            'text-red-600' => $pred['accuracy'] < 60,
                                        ])>
                                            {{ $pred['accuracy'] }}%
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-500 font-mono text-xs">{{ $pred['version'] }}</td>
                                <td class="px-4 py-2 text-gray-400 text-xs">{{ $pred['created'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-400">{{ __('app.predictions.no_predictions_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
