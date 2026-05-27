<div class="space-y-8">
    {{-- Search Section --}}
    <div class="text-center space-y-4">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('portal.heading') }}</h1>
        <p class="text-gray-500">{{ __('portal.subheading') }}</p>

        <form wire:submit="search" class="max-w-md mx-auto flex gap-2">
            <input
                type="text"
                wire:model="trackingCode"
                placeholder="{{ __('portal.placeholder') }}"
                class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-center text-lg tracking-widest uppercase"
                maxlength="8"
            />
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                {{ __('portal.search') }}
            </button>
        </form>
    </div>

    {{-- Error --}}
    @if ($errorMessage)
        <div class="max-w-md mx-auto bg-red-50 border border-red-200 rounded-lg p-4 text-center">
            <p class="text-red-700">{{ $errorMessage }}</p>
        </div>
    @endif

    {{-- Order Details --}}
    @if ($order)
        <div class="max-w-2xl mx-auto space-y-6">
            {{-- Status Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-blue-200 text-sm">{{ __('portal.order_number') }}</p>
                            <p class="text-2xl font-bold">#{{ $order->id }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-blue-200 text-sm">{{ __('portal.status') }}</p>
                            <p class="text-lg font-semibold">{{ $order->status->label() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">{{ __('portal.product_type') }}</p>
                        <p class="font-medium text-gray-900">{{ $order->productType?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">{{ __('portal.due_date') }}</p>
                        <p class="font-medium text-gray-900">{{ $order->due_date?->format('M d, Y') ?? '-' }}</p>
                    </div>
                    @if ($order->predicted_completion_at)
                        <div>
                            <p class="text-xs text-gray-500 uppercase">{{ __('portal.estimated_completion') }}</p>
                            <p class="font-medium text-blue-600">{{ $order->predicted_completion_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 uppercase">{{ __('portal.progress') }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="flex-1 bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ $order->progressPercentage() }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ $order->progressPercentage() }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Steps Timeline --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('portal.production_steps') }}</h3>
                <div class="space-y-4">
                    @foreach ($order->steps as $step)
                        <div class="flex items-center gap-4">
                            <div @class([
                                'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0',
                                'bg-green-100 text-green-700' => $step->status->value === 'done',
                                'bg-blue-100 text-blue-700' => $step->status->value === 'in_progress',
                                'bg-gray-100 text-gray-500' => $step->status->value === 'pending',
                                'bg-yellow-100 text-yellow-700' => $step->status->value === 'skipped',
                            ])>
                                @if ($step->status->value === 'done')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @elseif ($step->status->value === 'in_progress')
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                @else
                                    {{ $step->sort_order }}
                                @endif
                            </div>
                            <div class="flex-1">
                                <p @class([
                                    'font-medium',
                                    'text-gray-900' => $step->status->value !== 'pending',
                                    'text-gray-400' => $step->status->value === 'pending',
                                ])>{{ $step->step_name }}</p>
                            </div>
                            <span @class([
                                'text-xs px-2 py-1 rounded-full font-medium',
                                'bg-green-100 text-green-700' => $step->status->value === 'done',
                                'bg-blue-100 text-blue-700' => $step->status->value === 'in_progress',
                                'bg-gray-100 text-gray-500' => $step->status->value === 'pending',
                                'bg-yellow-100 text-yellow-700' => $step->status->value === 'skipped',
                            ])>
                                {{ $step->status->label() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @elseif ($searched && !$errorMessage)
        <div class="text-center text-gray-400 py-8">
            {{ __('portal.no_results') }}
        </div>
    @endif
</div>
