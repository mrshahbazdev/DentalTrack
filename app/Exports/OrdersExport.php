<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private readonly string $dateFrom,
        private readonly string $dateTo,
        private readonly ?int $companyId = null,
    ) {}

    public function collection(): Collection
    {
        $query = Order::with(['company', 'lab', 'productType', 'steps'])
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo.' 23:59:59'])
            ->orderByDesc('created_at');

        if ($this->companyId !== null) {
            $query->where('company_id', $this->companyId);
        }

        return $query->get();
    }

    /** @return array<int, string> */
    public function headings(): array
    {
        return [
            'Order ID',
            'Company',
            'Lab',
            'Product Type',
            'Patient Ref',
            'Doctor',
            'Priority',
            'Status',
            'Due Date',
            'Created At',
            'Completed At',
            'Total Steps',
            'Steps Done',
            'Progress %',
        ];
    }

    /**
     * @param  Order  $row
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        /** @var Order $order */
        $order = $row;

        return [
            $order->id,
            $order->company->name ?? '',
            $order->lab->name ?? '',
            $order->productType->name ?? '',
            $order->patient_ref,
            $order->doctor_name,
            $order->priority->value,
            $order->status->value,
            $order->due_date?->format('Y-m-d'),
            $order->created_at?->format('Y-m-d H:i'),
            $order->completed_at instanceof Carbon ? $order->completed_at->format('Y-m-d H:i') : '',
            $order->totalStepsCount(),
            $order->completedStepsCount(),
            $order->progressPercentage().'%',
        ];
    }
}
