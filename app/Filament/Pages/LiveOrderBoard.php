<?php

namespace App\Filament\Pages;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class LiveOrderBoard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-tv';

    protected static ?string $navigationLabel = 'Live Order Board';

    protected static ?string $title = 'Live Order Board';

    protected static ?int $navigationSort = -1;

    protected static string $view = 'filament.pages.live-order-board';

    public string $searchQuery = '';

    public string $priorityFilter = '';

    /**
     * @return Collection<int, Order>
     */
    public function getInProgressOrders(): Collection
    {
        return $this->getFilteredQuery()
            ->where('status', OrderStatus::InProgress)
            ->with(['productType', 'lab', 'scanEvents.workstation', 'scanEvents.user'])
            ->orderBy('priority', 'desc')
            ->limit(50)
            ->get();
    }

    /**
     * @return Collection<int, Order>
     */
    public function getWaitingOrders(): Collection
    {
        return $this->getFilteredQuery()
            ->where('status', OrderStatus::Pending)
            ->with(['productType', 'lab'])
            ->orderBy('due_date')
            ->limit(50)
            ->get();
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOverdueOrders(): Collection
    {
        return $this->getFilteredQuery()
            ->where('due_date', '<', now())
            ->whereNotIn('status', [OrderStatus::Completed, OrderStatus::Cancelled])
            ->with(['productType', 'lab', 'scanEvents.workstation'])
            ->orderBy('due_date')
            ->limit(50)
            ->get();
    }

    /**
     * @return Builder<Order>
     */
    private function getFilteredQuery(): Builder
    {
        $query = Order::query();

        if ($this->searchQuery !== '') {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->searchQuery}%")
                    ->orWhere('patient_ref', 'like', "%{$this->searchQuery}%")
                    ->orWhere('doctor_name', 'like', "%{$this->searchQuery}%");
            });
        }

        if ($this->priorityFilter !== '') {
            $query->where('priority', $this->priorityFilter);
        }

        return $query;
    }
}
