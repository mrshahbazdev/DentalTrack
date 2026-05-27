<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\ScanEvent;
use App\Models\Workstation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardCacheService
{
    private const TTL_SECONDS = 60;

    private const PREFIX = 'dashboard:';

    /** @return array{in_progress: int, pending: int, completed: int, overdue: int, total: int} */
    public function getOrderCounts(?int $companyId = null): array
    {
        $key = self::PREFIX.'order_counts:'.($companyId ?? 'all');

        $result = Cache::remember($key, self::TTL_SECONDS, function () use ($companyId): array {
            $query = Order::query();
            if ($companyId !== null) {
                $query->where('company_id', $companyId);
            }

            return [
                'in_progress' => (int) (clone $query)->where('status', OrderStatus::InProgress)->count(),
                'pending' => (int) (clone $query)->where('status', OrderStatus::Pending)->count(),
                'completed' => (int) (clone $query)->where('status', OrderStatus::Completed)->count(),
                'overdue' => (int) (clone $query)->where('due_date', '<', now())
                    ->whereNotIn('status', [OrderStatus::Completed->value, OrderStatus::Cancelled->value])
                    ->count(),
                'total' => (int) $query->count(),
            ];
        });

        /** @var array{in_progress: int, pending: int, completed: int, overdue: int, total: int} $result */
        return $result;
    }

    /** @return array<int, array{id: int, name: string, active_orders: int, idle: bool}> */
    public function getWorkstationStatus(?int $labId = null): array
    {
        $key = self::PREFIX.'workstation_status:'.($labId ?? 'all');

        $result = Cache::remember($key, self::TTL_SECONDS, function () use ($labId): array {
            $query = Workstation::where('is_active', true);
            if ($labId !== null) {
                $query->where('lab_id', $labId);
            }

            $workstations = $query->get();
            $statuses = [];
            $cutoff = Carbon::now()->subHours(8);

            foreach ($workstations as $ws) {
                $activeCount = ScanEvent::where('workstation_id', $ws->id)
                    ->where('event_type', 'start')
                    ->where('scanned_at', '>=', $cutoff)
                    ->distinct('order_id')
                    ->count('order_id');

                $statuses[] = [
                    'id' => $ws->id,
                    'name' => $ws->name,
                    'active_orders' => $activeCount,
                    'idle' => $activeCount === 0,
                ];
            }

            return $statuses;
        });

        /** @var array<int, array{id: int, name: string, active_orders: int, idle: bool}> $result */
        return $result;
    }

    public function invalidateOrderCaches(): void
    {
        Cache::forget(self::PREFIX.'order_counts:all');
        Cache::forget(self::PREFIX.'workstation_status:all');
    }

    public function warmCaches(): void
    {
        $this->getOrderCounts();
        $this->getWorkstationStatus();
    }
}
