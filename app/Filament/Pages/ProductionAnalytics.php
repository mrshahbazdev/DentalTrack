<?php

namespace App\Filament\Pages;

use App\Enums\OrderStatus;
use App\Enums\ScanEventType;
use App\Models\Lab;
use App\Models\Order;
use App\Models\ProductType;
use App\Models\Workstation;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductionAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Production Analytics';

    protected static ?string $navigationGroup = 'Analytics';

    protected static ?int $navigationSort = 11;

    protected static string $view = 'filament.pages.production-analytics';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $selectedCompany = '';

    public function mount(): void
    {
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    /** @return array{total_orders: int, completed: int, in_progress: int, overdue: int, on_time_pct: float, completion_rate: float} */
    public function getSummaryStats(): array
    {
        $query = Order::query()
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo.' 23:59:59']);

        if ($this->selectedCompany !== '') {
            $query->where('company_id', $this->selectedCompany);
        }

        $total = $query->count();
        $completed = (clone $query)->where('status', OrderStatus::Completed)->count();
        $inProgress = (clone $query)->where('status', OrderStatus::InProgress)->count();
        $overdue = (clone $query)->where('due_date', '<', Carbon::now())
            ->whereNotIn('status', [OrderStatus::Completed, OrderStatus::Cancelled])
            ->count();

        $onTimePct = $completed > 0 ? round(
            (clone $query)->where('status', OrderStatus::Completed)
                ->whereColumn('completed_at', '<=', 'due_date')
                ->count() / $completed * 100,
            1
        ) : 0.0;

        return [
            'total_orders' => $total,
            'completed' => $completed,
            'in_progress' => $inProgress,
            'overdue' => $overdue,
            'on_time_pct' => $onTimePct,
            'completion_rate' => $total > 0 ? round($completed / $total * 100, 1) : 0.0,
        ];
    }

    /** @return array<int, array{workstation: string, lab: string, avg_minutes: float, event_count: int}> */
    public function getBottleneckAnalysis(): array
    {
        $rows = DB::table('scan_events')
            ->where('event_type', ScanEventType::Complete->value)
            ->whereNotNull('duration_seconds')
            ->whereBetween('scanned_at', [$this->dateFrom, $this->dateTo.' 23:59:59'])
            ->select('workstation_id', DB::raw('AVG(duration_seconds) as avg_duration'), DB::raw('COUNT(*) as event_count'))
            ->groupBy('workstation_id')
            ->orderByDesc('avg_duration')
            ->limit(10)
            ->get();

        $results = [];
        foreach ($rows as $row) {
            $ws = Workstation::with('lab')->find($row->workstation_id);
            $wsName = 'Unknown';
            $labName = 'N/A';
            if ($ws instanceof Workstation) {
                $wsName = $ws->name;
                $wsLab = $ws->lab;
                if ($wsLab instanceof Lab) {
                    $labName = $wsLab->name;
                }
            }

            $results[] = [
                'workstation' => $wsName,
                'lab' => $labName,
                'avg_minutes' => round((float) $row->avg_duration / 60, 1),
                'event_count' => (int) $row->event_count,
            ];
        }

        return $results;
    }

    /** @return array<int, array{day: string, count: int}> */
    public function getThroughputByDay(): array
    {
        $rows = DB::table('orders')
            ->where('status', OrderStatus::Completed->value)
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$this->dateFrom, $this->dateTo.' 23:59:59']);

        if ($this->selectedCompany !== '') {
            $rows->where('company_id', $this->selectedCompany);
        }

        $rows = $rows
            ->select(DB::raw('DATE(completed_at) as day'), DB::raw('COUNT(*) as count'))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $results = [];
        foreach ($rows as $row) {
            $results[] = [
                'day' => Carbon::parse($row->day)->format('M d'),
                'count' => (int) $row->count,
            ];
        }

        return $results;
    }

    /** @return array<int, array{product_type: string, count: int, avg_minutes: int, avg_hours: float}> */
    public function getProductTypeBreakdown(): array
    {
        $query = Order::where('status', OrderStatus::Completed)
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$this->dateFrom, $this->dateTo.' 23:59:59']);

        if ($this->selectedCompany !== '') {
            $query->where('company_id', $this->selectedCompany);
        }

        $grouped = $query->with('productType')->get()->groupBy('product_type_id');

        $results = [];
        foreach ($grouped as $orders) {
            $firstOrder = $orders->first();
            if (! $firstOrder instanceof Order) {
                continue;
            }

            $pt = $firstOrder->productType;
            $ptName = $pt instanceof ProductType ? $pt->name : 'Unknown';
            $totalMinutes = 0;

            foreach ($orders as $order) {
                $totalMinutes += $order->scanEvents()
                    ->where('event_type', ScanEventType::Complete)
                    ->sum('duration_seconds') / 60;
            }

            $avgMinutes = $orders->count() > 0 ? (int) round($totalMinutes / $orders->count()) : 0;

            $results[] = [
                'product_type' => $ptName,
                'count' => $orders->count(),
                'avg_minutes' => $avgMinutes,
                'avg_hours' => round($avgMinutes / 60, 1),
            ];
        }

        usort($results, static function (array $a, array $b): int {
            return (int) $b['count'] <=> (int) $a['count'];
        });

        return $results;
    }
}
