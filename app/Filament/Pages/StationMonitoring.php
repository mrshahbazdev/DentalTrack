<?php

namespace App\Filament\Pages;

use App\Enums\ScanEventType;
use App\Models\Lab;
use App\Models\Order;
use App\Models\Workstation;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StationMonitoring extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $navigationLabel = 'Stationsmonitoring';

    protected static ?string $navigationGroup = 'Monitoring';

    protected static ?int $navigationSort = 20;

    protected static string $view = 'filament.pages.station-monitoring';

    public string $dateFrom = '';

    public string $dateTo = '';

    public function mount(): void
    {
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function getTitle(): string
    {
        return __('app.monitoring.title');
    }

    /** @return array<int, array{name: string, type: string, lab: string, avg_duration: string, min_duration: string, max_duration: string, total_events: int, active_orders: int}> */
    public function getStationStats(): array
    {
        $rows = DB::table('scan_events')
            ->where('event_type', ScanEventType::Complete->value)
            ->whereNotNull('duration_seconds')
            ->whereBetween('scanned_at', [$this->dateFrom, $this->dateTo . ' 23:59:59'])
            ->select(
                'workstation_id',
                DB::raw('AVG(duration_seconds) as avg_duration'),
                DB::raw('MIN(duration_seconds) as min_duration'),
                DB::raw('MAX(duration_seconds) as max_duration'),
                DB::raw('COUNT(*) as total_events')
            )
            ->groupBy('workstation_id')
            ->orderByDesc('avg_duration')
            ->get();

        $results = [];
        foreach ($rows as $row) {
            $ws = Workstation::with('lab')->find($row->workstation_id);
            if (!$ws instanceof Workstation) {
                continue;
            }

            $wsLab = $ws->lab;
            $labName = $wsLab instanceof Lab ? $wsLab->name : 'N/A';

            $activeOrders = DB::table('scan_events')
                ->where('workstation_id', $row->workstation_id)
                ->where('event_type', ScanEventType::Start->value)
                ->whereNotExists(function ($query) use ($row) {
                    $query->select(DB::raw(1))
                        ->from('scan_events as se2')
                        ->whereColumn('se2.order_id', 'scan_events.order_id')
                        ->where('se2.workstation_id', $row->workstation_id)
                        ->where('se2.event_type', ScanEventType::Complete->value)
                        ->whereColumn('se2.scanned_at', '>', 'scan_events.scanned_at');
                })
                ->distinct('order_id')
                ->count('order_id');

            $results[] = [
                'name' => $ws->name,
                'type' => $ws->type->label(),
                'lab' => $labName,
                'avg_duration' => $this->formatDuration((int) round((float) $row->avg_duration)),
                'min_duration' => $this->formatDuration((int) $row->min_duration),
                'max_duration' => $this->formatDuration((int) $row->max_duration),
                'total_events' => (int) $row->total_events,
                'active_orders' => $activeOrders,
            ];
        }

        return $results;
    }

    /** @return Collection<int, array{order_id: int, workstation: string, technician: string, event_type: string, duration: string, time: string}> */
    public function getRecentActivity(): Collection
    {
        $events = DB::table('scan_events')
            ->join('workstations', 'scan_events.workstation_id', '=', 'workstations.id')
            ->leftJoin('users', 'scan_events.user_id', '=', 'users.id')
            ->whereBetween('scan_events.scanned_at', [$this->dateFrom, $this->dateTo . ' 23:59:59'])
            ->select(
                'scan_events.order_id',
                'workstations.name as workstation_name',
                'users.name as technician_name',
                'scan_events.event_type',
                'scan_events.duration_seconds',
                'scan_events.scanned_at'
            )
            ->orderByDesc('scan_events.scanned_at')
            ->limit(20)
            ->get();

        return $events->map(function ($event) {
            $eventType = ScanEventType::tryFrom($event->event_type);

            return [
                'order_id' => $event->order_id,
                'workstation' => $event->workstation_name,
                'technician' => $event->technician_name ?? 'N/A',
                'event_type' => $eventType ? $eventType->label() : $event->event_type,
                'duration' => $event->duration_seconds ? $this->formatDuration((int) $event->duration_seconds) : '-',
                'time' => Carbon::parse($event->scanned_at)->format('d.m.Y H:i'),
            ];
        });
    }

    private function formatDuration(int $seconds): string
    {
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $minutes);
        }

        if ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $secs);
        }

        return sprintf('%ds', $secs);
    }
}
