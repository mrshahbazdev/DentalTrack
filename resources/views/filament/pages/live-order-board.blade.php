<x-filament-panels::page>
    <div class="space-y-6" wire:poll.5s>
        {{-- Filters --}}
        <div class="flex flex-wrap gap-4 items-center">
            <x-filament::input.wrapper>
                <x-filament::input
                    type="text"
                    wire:model.live.debounce.300ms="searchQuery"
                    placeholder="Search orders..."
                />
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="priorityFilter">
                    <option value="">All Priorities</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">High</option>
                    <option value="normal">Normal</option>
                    <option value="low">Low</option>
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>

        {{-- Overdue Orders --}}
        @php $overdueOrders = $this->getOverdueOrders(); @endphp
        @if($overdueOrders->isNotEmpty())
            <div>
                <h2 class="text-lg font-bold text-red-600 mb-3">
                    OVERDUE ({{ $overdueOrders->count() }})
                </h2>
                <div class="bg-red-50 border border-red-200 rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-red-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-red-800">Order #</th>
                                <th class="px-4 py-2 text-left font-medium text-red-800">Product</th>
                                <th class="px-4 py-2 text-left font-medium text-red-800">Due Date</th>
                                <th class="px-4 py-2 text-left font-medium text-red-800">Current Station</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overdueOrders as $order)
                                <tr class="border-t border-red-200">
                                    <td class="px-4 py-2 text-red-700 font-semibold">#{{ $order->id }}</td>
                                    <td class="px-4 py-2 text-red-700">{{ $order->productType->name }}</td>
                                    <td class="px-4 py-2 text-red-700">{{ $order->due_date?->format('M d, Y') }}</td>
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
                IN PROGRESS ({{ $inProgressOrders->count() }})
            </h2>
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Order #</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Product</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Current Station</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Technician</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Priority</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Progress</th>
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
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">No orders in progress</td>
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
                PENDING ({{ $waitingOrders->count() }})
            </h2>
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Order #</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Product</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Lab</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Due Date</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($waitingOrders as $order)
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3 font-semibold">#{{ $order->id }}</td>
                                <td class="px-4 py-3">{{ $order->productType->name }}</td>
                                <td class="px-4 py-3">{{ $order->lab->name }}</td>
                                <td class="px-4 py-3">{{ $order->due_date?->format('M d, Y') ?? '-' }}</td>
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
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">No pending orders</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
