<?php

namespace App\Filament\Pages;

use App\Enums\ScanEventType;
use App\Models\Workstation;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BroadcastsOverview extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-signal';

    public static function getNavigationLabel(): string
    {
        return __('app.nav.broadcasts_overview');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.nav.monitoring');
    }

    protected static ?int $navigationSort = 21;

    protected static string $view = 'filament.pages.broadcasts-overview';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $eventTypeFilter = '';

    public function mount(): void
    {
        $this->dateFrom = Carbon::now()->subDays(7)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function getTitle(): string
    {
        return __('app.broadcasts.title');
    }

    /** @return array{total: int, today: int, last_hour: int} */
    public function getSummaryStats(): array
    {
        $baseQuery = DB::table('scan_events')
            ->whereBetween('scanned_at', [$this->dateFrom, $this->dateTo.' 23:59:59']);

        if ($this->eventTypeFilter !== '') {
            $baseQuery->where('event_type', $this->eventTypeFilter);
        }

        $total = (clone $baseQuery)->count();
        $today = (clone $baseQuery)->whereDate('scanned_at', Carbon::today())->count();
        $lastHour = (clone $baseQuery)->where('scanned_at', '>=', Carbon::now()->subHour())->count();

        return [
            'total' => $total,
            'today' => $today,
            'last_hour' => $lastHour,
        ];
    }

    /** @return Collection<int, array{order_id: int, channel: string, event_type: string, workstation: string, technician: string, triggered_at: string, payload: string}> */
    public function getBroadcastEvents(): Collection
    {
        $query = DB::table('scan_events')
            ->join('workstations', 'scan_events.workstation_id', '=', 'workstations.id')
            ->leftJoin('users', 'scan_events.user_id', '=', 'users.id')
            ->leftJoin('orders', 'scan_events.order_id', '=', 'orders.id')
            ->whereBetween('scan_events.scanned_at', [$this->dateFrom, $this->dateTo.' 23:59:59']);

        if ($this->eventTypeFilter !== '') {
            $query->where('scan_events.event_type', $this->eventTypeFilter);
        }

        $events = $query
            ->select(
                'scan_events.order_id',
                'scan_events.event_type',
                'scan_events.scanned_at',
                'scan_events.duration_seconds',
                'scan_events.notes',
                'workstations.name as workstation_name',
                'users.name as technician_name',
                'orders.company_id'
            )
            ->orderByDesc('scan_events.scanned_at')
            ->limit(50)
            ->get();

        return $events->map(function ($event) {
            $eventType = ScanEventType::tryFrom($event->event_type);
            $channel = $event->company_id
                ? "company.{$event->company_id}.orders"
                : 'N/A';

            $payload = array_filter([
                'duration' => $event->duration_seconds ? "{$event->duration_seconds}s" : null,
                'notes' => $event->notes,
            ]);

            return [
                'order_id' => $event->order_id,
                'channel' => $channel,
                'event_type' => $eventType ? $eventType->label() : $event->event_type,
                'workstation' => $event->workstation_name,
                'technician' => $event->technician_name ?? 'N/A',
                'triggered_at' => Carbon::parse($event->scanned_at)->format('d.m.Y H:i:s'),
                'payload' => ! empty($payload) ? json_encode($payload, JSON_UNESCAPED_UNICODE) : '-',
            ];
        });
    }
}
