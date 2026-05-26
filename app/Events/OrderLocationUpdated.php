<?php

namespace App\Events;

use App\Models\Order;
use App\Models\ScanEvent;
use App\Models\User;
use App\Models\Workstation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderLocationUpdated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Order $order,
    ) {}

    /** @return array<int, Channel> */
    public function broadcastOn(): array
    {
        return [
            new Channel("company.{$this->order->company_id}.orders"),
        ];
    }

    /** @return array<string, mixed> */
    public function broadcastWith(): array
    {
        $this->order->load(['productType', 'lab']);

        /** @var ScanEvent|null $latestScan */
        $latestScan = $this->order->latestScanEvent();

        /** @var Workstation|null $workstation */
        $workstation = $latestScan?->workstation;
        /** @var User|null $scanUser */
        $scanUser = $latestScan?->user;

        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status->value,
            'priority' => $this->order->priority->value,
            'product_type' => $this->order->productType->name ?? '',
            'lab' => $this->order->lab->name ?? '',
            'current_workstation' => $workstation?->name,
            'current_technician' => $scanUser?->name,
            'event_type' => $latestScan?->event_type?->value,
            'progress' => $this->order->progressPercentage(),
        ];
    }
}
