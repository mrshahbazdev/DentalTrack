<?php

namespace App\Filament\Pages;

use App\Enums\ScanEventType;
use App\Models\Company;
use App\Models\ScanEvent;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class EmployeePerformance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Employee Performance';

    protected static ?string $navigationGroup = 'Analytics';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.employee-performance';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $selectedCompany = '';

    public function mount(): void
    {
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    /**
     * @return array<int, array{id: int, name: string, company: string, steps_completed: int, orders_completed: int, avg_time_per_step: int, orders_per_day: float, total_hours: float, utilization_pct: float}>
     */
    public function getTechnicians(): array
    {
        $query = User::where('role', 'technician')->where('is_active', true);

        if ($this->selectedCompany !== '') {
            $query->where('company_id', $this->selectedCompany);
        }

        $technicians = $query->get();
        $results = [];

        foreach ($technicians as $user) {
            $events = ScanEvent::where('user_id', $user->id)
                ->whereBetween('scanned_at', [$this->dateFrom, $this->dateTo.' 23:59:59']);

            $completedEvents = (clone $events)->where('event_type', ScanEventType::Complete)->get();

            $totalDurationSeconds = $completedEvents->sum('duration_seconds');
            $ordersCompleted = $completedEvents->pluck('order_id')->unique()->count();

            $days = max(1, Carbon::parse($this->dateFrom)->diffInDays(Carbon::parse($this->dateTo)) + 1);
            $workingDays = max(1, min($days, (int) ceil($days * 5 / 7)));

            $avgTimePerStep = $completedEvents->count() > 0
                ? (int) round($totalDurationSeconds / $completedEvents->count() / 60)
                : 0;

            $ordersPerDay = round($ordersCompleted / $workingDays, 1);

            $totalAvailableSeconds = $workingDays * 8 * 3600;
            $utilizationPct = $totalAvailableSeconds > 0
                ? round(($totalDurationSeconds / $totalAvailableSeconds) * 100, 1)
                : 0.0;

            $relatedCompany = $user->company;
            $companyName = $relatedCompany instanceof Company ? $relatedCompany->name : 'N/A';

            $results[] = [
                'id' => $user->id,
                'name' => $user->name,
                'company' => $companyName,
                'steps_completed' => $completedEvents->count(),
                'orders_completed' => $ordersCompleted,
                'avg_time_per_step' => $avgTimePerStep,
                'orders_per_day' => $ordersPerDay,
                'total_hours' => round($totalDurationSeconds / 3600, 1),
                'utilization_pct' => $utilizationPct,
            ];
        }

        usort($results, static function (array $a, array $b): int {
            return (int) $b['steps_completed'] <=> (int) $a['steps_completed'];
        });

        return $results;
    }
}
