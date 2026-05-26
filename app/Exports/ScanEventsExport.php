<?php

namespace App\Exports;

use App\Models\ScanEvent;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScanEventsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private readonly string $dateFrom,
        private readonly string $dateTo,
        private readonly ?int $companyId = null,
    ) {}

    public function collection(): Collection
    {
        $query = ScanEvent::with(['order', 'orderStep', 'workstation', 'user'])
            ->whereBetween('scanned_at', [$this->dateFrom, $this->dateTo.' 23:59:59'])
            ->orderByDesc('scanned_at');

        if ($this->companyId !== null) {
            $query->whereHas('order', fn ($q) => $q->where('company_id', $this->companyId));
        }

        return $query->get();
    }

    /** @return array<int, string> */
    public function headings(): array
    {
        return [
            'Event ID',
            'Order ID',
            'Step',
            'Workstation',
            'Technician',
            'Event Type',
            'Scanned At',
            'Duration (min)',
        ];
    }

    /**
     * @param  ScanEvent  $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        /** @var ScanEvent $event */
        $event = $row;

        return [
            $event->id,
            $event->order_id,
            $event->orderStep->step_name ?? '',
            $event->workstation->name ?? '',
            $event->user->name ?? '',
            $event->event_type->value,
            $event->scanned_at->format('Y-m-d H:i:s'),
            $event->duration_seconds !== null ? round($event->duration_seconds / 60, 1) : '',
        ];
    }
}
