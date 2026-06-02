<x-filament-panels::page>
    <div class="space-y-6" wire:poll.5s>
        {{-- Filters --}}
        <div class="flex flex-wrap gap-4 items-center">
            <x-filament::input.wrapper>
                <x-filament::input
                    type="text"
                    wire:model.live.debounce.300ms="searchQuery"
                    placeholder="{{ __('app.common.search_orders') }}"
                />
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="priorityFilter">
                    <option value="">{{ __('app.common.all_priorities') }}</option>
                    <option value="urgent">{{ __('app.priority.urgent') }}</option>
                    <option value="high">{{ __('app.priority.high') }}</option>
                    <option value="normal">{{ __('app.priority.normal') }}</option>
                    <option value="low">{{ __('app.priority.low') }}</option>
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>

        {{-- Overdue Orders --}}
        @php $overdueOrders = $this->getOverdueOrders(); @endphp
        @if($overdueOrders->isNotEmpty())
            <div>
                <h2 class="text-lg font-bold text-red-600 mb-3">
                    {{ __('app.board.overdue') }} ({{ $overdueOrders->count() }})
                </h2>
                <div class="bg-red-50 border border-red-200 rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-red-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-red-800">{{ __('app.common.order_number') }}</th>
                                <th class="px-4 py-2 text-left font-medium text-red-800">{{ __('app.common.product') }}</th>
                                <th class="px-4 py-2 text-left font-medium text-red-800">{{ __('app.common.due_date') }}</th>
                                <th class="px-4 py-2 text-left font-medium text-red-800">{{ __('app.common.current_station') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overdueOrders as $order)
                                <tr class="border-t border-red-200">
                                    <td class="px-4 py-2 text-red-700 font-semibold">#{{ $order->id }}</td>
                                    <td class="px-4 py-2 text-red-700">{{ $order->productType->name }}</td>
                                    <td class="px-4 py-2 text-red-700">{{ $order->due_date?->format('d.m.Y') }}</td>
                                    <td class="px-4 py-2 text-red-700">
                                        {{ $order->scanEvents->first()?->workstation?->name ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- In Progress Orders --}}
        @php $inProgressOrders = $this->getInProgressOrders(); @endphp
        <div>
            <h2 class="text-lg font-bold text-blue-600 mb-3">
                {{ __('app.board.in_progress') }} ({{ $inProgressOrders->count() }})
            </h2>
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.order_number') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.product') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.current_station') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.technician') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.priority') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.eta') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.progress') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inProgressOrders as $order)
                            @php
                                $latestScan = $order->scanEvents->first();
                            @endphp
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3 font-semibold">#{{ $order->id }}</td>
                                <td class="px-4 py-3">{{ $order->productType->name }}</td>
                                <td class="px-4 py-3">
                                    {{ $latestScan?->workstation?->name ?? 'N/A' }}
                                    @if($latestScan)
                                        <span class="text-xs text-gray-400">
                                            ({{ $latestScan->scanned_at->diffForHumans() }})
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $latestScan?->user?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium',
                                        'bg-red-100 text-red-700' => $order->priority->value === 'urgent',
                                        'bg-orange-100 text-orange-700' => $order->priority->value === 'high',
                                        'bg-blue-100 text-blue-700' => $order->priority->value === 'normal',
                                        'bg-gray-100 text-gray-700' => $order->priority->value === 'low',
                                    ])>
                                        {{ $order->priority->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($order->predicted_completion_at)
                                        <span class="text-xs {{ $order->predicted_completion_at->isPast() ? 'text-red-600 font-bold' : 'text-blue-600' }}">
                                            {{ $order->predicted_completion_at->format('d.m, H:i') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $order->progressPercentage() }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $order->progressPercentage() }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-400">{{ __('app.board.no_orders_in_progress') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Waiting/Pending Orders --}}
        @php $waitingOrders = $this->getWaitingOrders(); @endphp
        <div>
            <h2 class="text-lg font-bold text-yellow-600 mb-3">
                {{ __('app.board.pending') }} ({{ $waitingOrders->count() }})
            </h2>
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.order_number') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.product') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.lab') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.due_date') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">{{ __('app.common.priority') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($waitingOrders as $order)
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3 font-semibold">#{{ $order->id }}</td>
                                <td class="px-4 py-3">{{ $order->productType->name }}</td>
                                <td class="px-4 py-3">{{ $order->lab->name }}</td>
                                <td class="px-4 py-3">{{ $order->due_date?->format('d.m.Y') ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium',
                                        'bg-red-100 text-red-700' => $order->priority->value === 'urgent',
                                        'bg-orange-100 text-orange-700' => $order->priority->value === 'high',
                                        'bg-blue-100 text-blue-700' => $order->priority->value === 'normal',
                                        'bg-gray-100 text-gray-700' => $order->priority->value === 'low',
                                    ])>
                                        {{ $order->priority->label() }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">{{ __('app.board.no_orders_pending') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
