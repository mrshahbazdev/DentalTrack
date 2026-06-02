<?php

namespace App\Filament\Pages;

use App\Enums\OrderStatus;
use App\Enums\ScanEventType;
use App\Models\Company;
use App\Models\Lab;
use App\Models\Order;
use App\Models\ScanEvent;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class CompanyComparison extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Firmenvergleich';

    protected static ?string $navigationGroup = 'Analysen';

    protected static ?int $navigationSort = 12;

    protected static string $view = 'filament.pages.company-comparison';

    public string $dateFrom = '';

    public string $dateTo = '';

    public function mount(): void
    {
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    /**
     * @return array<int, array{name: string, total_orders: int, completed: int, overdue: int, on_time_pct: float, avg_step_minutes: float, technicians: int, workstations: int, orders_per_day: float, completion_rate: float}>
     */
    public function getCompanyKpis(): array
    {
        $companies = Company::all();
        $results = [];

        foreach ($companies as $company) {
            $orders = Order::where('company_id', $company->id)
                ->whereBetween('created_at', [$this->dateFrom, $this->dateTo.' 23:59:59']);

            $totalOrders = (clone $orders)->count();
            $completedOrders = (clone $orders)->where('status', OrderStatus::Completed)->count();
            $overdueOrders = (clone $orders)
                ->where('due_date', '<', Carbon::now())
                ->whereNotIn('status', [OrderStatus::Completed, OrderStatus::Cancelled])
                ->count();

            $onTimeCount = (clone $orders)
                ->where('status', OrderStatus::Completed)
                ->whereColumn('completed_at', '<=', 'due_date')
                ->count();
            $onTimePct = $completedOrders > 0 ? round($onTimeCount / $completedOrders * 100, 1) : 0.0;

            $avgStepMinutes = ScanEvent::whereHas('order', fn ($q) => $q->where('company_id', $company->id))
                ->where('event_type', ScanEventType::Complete)
                ->whereNotNull('duration_seconds')
                ->whereBetween('scanned_at', [$this->dateFrom, $this->dateTo.' 23:59:59'])
                ->avg('duration_seconds');

            $avgStepMinutes = $avgStepMinutes !== null ? round((float) $avgStepMinutes / 60, 1) : 0.0;

            $technicians = $company->users()->where('role', 'technician')->where('is_active', true)->count();
            $workstationsCount = 0;
            foreach ($company->labs as $lab) {
                if ($lab instanceof Lab) {
                    $workstationsCount += $lab->workstations()->count();
                }
            }

            $days = max(1, Carbon::parse($this->dateFrom)->diffInDays(Carbon::parse($this->dateTo)) + 1);
            $ordersPerDay = $days > 0 ? round($completedOrders / $days, 1) : 0.0;

            $results[] = [
                'name' => $company->name,
                'total_orders' => $totalOrders,
                'completed' => $completedOrders,
                'overdue' => $overdueOrders,
                'on_time_pct' => $onTimePct,
                'avg_step_minutes' => $avgStepMinutes,
                'technicians' => $technicians,
                'workstations' => $workstationsCount,
                'orders_per_day' => $ordersPerDay,
                'completion_rate' => $totalOrders > 0 ? round($completedOrders / $totalOrders * 100, 1) : 0.0,
            ];
        }

        return $results;
    }
}
