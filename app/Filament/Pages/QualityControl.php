<?php

namespace App\Filament\Pages;

use App\Enums\ReworkCause;
use App\Enums\ReworkStatus;
use App\Models\Order;
use App\Models\OrderStep;
use App\Models\ReworkEvent;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class QualityControl extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function getNavigationLabel(): string
    {
        return __('app.nav.quality_dashboard');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.nav.quality_control');
    }

    protected static ?int $navigationSort = 21;

    protected static string $view = 'filament.pages.quality-control';

    public string $dateFrom = '';

    public string $dateTo = '';

    public function mount(): void
    {
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    /** @return array{total_orders: int, total_reworks: int, rework_rate: float, pending: int, in_rework: int, resolved: int} */
    public function getSummaryStats(): array
    {
        $totalOrders = Order::whereBetween('created_at', [$this->dateFrom, $this->dateTo.' 23:59:59'])->count();

        $reworkQuery = ReworkEvent::whereBetween('created_at', [$this->dateFrom, $this->dateTo.' 23:59:59']);
        $totalReworks = (clone $reworkQuery)->count();
        $pending = (clone $reworkQuery)->where('status', ReworkStatus::Pending)->count();
        $inRework = (clone $reworkQuery)->where('status', ReworkStatus::InRework)->count();
        $resolved = (clone $reworkQuery)->where('status', ReworkStatus::Resolved)->count();

        $reworkRate = $totalOrders > 0 ? round(($totalReworks / $totalOrders) * 100, 1) : 0.0;

        return [
            'total_orders' => $totalOrders,
            'total_reworks' => $totalReworks,
            'rework_rate' => $reworkRate,
            'pending' => $pending,
            'in_rework' => $inRework,
            'resolved' => $resolved,
        ];
    }

    /** @return array<int, array{cause: string, label: string, count: int, pct: float}> */
    public function getCauseBreakdown(): array
    {
        $rows = DB::table('rework_events')
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo.' 23:59:59'])
            ->select('cause', DB::raw('COUNT(*) as cnt'))
            ->groupBy('cause')
            ->orderByDesc('cnt')
            ->get();

        $total = $rows->sum('cnt');
        $results = [];

        foreach ($rows as $row) {
            $cause = ReworkCause::tryFrom((string) $row->cause);
            $results[] = [
                'cause' => (string) $row->cause,
                'label' => $cause instanceof ReworkCause ? $cause->label() : (string) $row->cause,
                'count' => (int) $row->cnt,
                'pct' => $total > 0 ? round(((int) $row->cnt / $total) * 100, 1) : 0,
            ];
        }

        return $results;
    }

    /** @return array<int, array{id: int, order_id: int, step: string, cause: string, status: string, flagged_by: string, created: string}> */
    public function getRecentReworks(): array
    {
        $events = ReworkEvent::with(['order', 'orderStep', 'flaggedByUser'])
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo.' 23:59:59'])
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $results = [];
        foreach ($events as $event) {
            $flaggedUser = $event->flaggedByUser;
            $step = $event->orderStep;
            $cause = $event->cause;
            $status = $event->status;

            $results[] = [
                'id' => $event->id,
                'order_id' => $event->order_id,
                'step' => $step instanceof OrderStep ? $step->step_name : 'Unknown',
                'cause' => $cause->label(),
                'status' => $status->label(),
                'flagged_by' => $flaggedUser instanceof User ? $flaggedUser->name : 'Unknown',
                'created' => $event->created_at !== null ? $event->created_at->diffForHumans() : '',
            ];
        }

        return $results;
    }

    /** @return array<int, array{user: string, rework_count: int, total_steps: int, rework_rate: float}> */
    public function getTechnicianQuality(): array
    {
        $rows = DB::table('rework_events')
            ->join('users', 'rework_events.original_technician', '=', 'users.id')
            ->whereBetween('rework_events.created_at', [$this->dateFrom, $this->dateTo.' 23:59:59'])
            ->whereNotNull('rework_events.original_technician')
            ->select('users.name', 'users.id as user_id', DB::raw('COUNT(*) as rework_count'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('rework_count')
            ->limit(10)
            ->get();

        $results = [];
        foreach ($rows as $row) {
            $totalSteps = DB::table('scan_events')
                ->where('user_id', $row->user_id)
                ->where('event_type', 'complete')
                ->whereBetween('scanned_at', [$this->dateFrom, $this->dateTo.' 23:59:59'])
                ->count();

            $results[] = [
                'user' => (string) $row->name,
                'rework_count' => (int) $row->rework_count,
                'total_steps' => $totalSteps,
                'rework_rate' => $totalSteps > 0 ? round(((int) $row->rework_count / $totalSteps) * 100, 1) : 0.0,
            ];
        }

        return $results;
    }
}
