<x-filament-panels::page>
    <div class="space-y-6" wire:poll.10s>
        {{-- Filters --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">From</label>
                    <input type="date" wire:model.live="dateFrom"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">To</label>
                    <input type="date" wire:model.live="dateTo"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Company</label>
                    <select wire:model.live="selectedCompany"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white text-sm">
                        <option value="">All Companies</option>
                        @foreach (\App\Models\Company::all() as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Performance Table --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Technician</th>
                            <th class="px-4 py-3">Company</th>
                            <th class="px-4 py-3 text-center">Steps Done</th>
                            <th class="px-4 py-3 text-center">Orders Done</th>
                            <th class="px-4 py-3 text-center">Avg Min/Step</th>
                            <th class="px-4 py-3 text-center">Orders/Day</th>
                            <th class="px-4 py-3 text-center">Total Hours</th>
                            <th class="px-4 py-3 text-center">Utilization</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($this->getTechnicians() as $tech)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $tech['name'] }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $tech['company'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-400">
                                        {{ $tech['steps_completed'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $tech['orders_completed'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if ($tech['avg_time_per_step'] > 0)
                                        <span class="{{ $tech['avg_time_per_step'] > 60 ? 'text-red-600' : ($tech['avg_time_per_step'] > 30 ? 'text-yellow-600' : 'text-green-600') }}">
                                            {{ $tech['avg_time_per_step'] }} min
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center font-medium">{{ $tech['orders_per_day'] }}</td>
                                <td class="px-4 py-3 text-center">{{ $tech['total_hours'] }}h</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 max-w-[80px]">
                                            <div class="h-2 rounded-full {{ $tech['utilization_pct'] > 70 ? 'bg-green-500' : ($tech['utilization_pct'] > 40 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                                style="width: {{ min(100, $tech['utilization_pct']) }}%"></div>
                                        </div>
                                        <span class="text-xs">{{ $tech['utilization_pct'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No technician data for the selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
