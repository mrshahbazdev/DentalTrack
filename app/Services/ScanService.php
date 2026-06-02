<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\ScanEventType;
use App\Enums\StepStatus;
use App\Events\OrderLocationUpdated;
use App\Models\Order;
use App\Models\ScanEvent;
use App\Models\User;
use App\Models\Workstation;
use Illuminate\Support\Carbon;

class ScanService
{
    public function startWork(Order $order, Workstation $workstation, User $user, ?string $notes = null): ScanEvent
    {
        $this->validateNotActiveElsewhere($order, $workstation);

        $currentStep = $order->currentStep();

        if ($currentStep !== null) {
            $currentStep->update([
                'status' => StepStatus::InProgress,
                'assigned_to' => $user->id,
            ]);
        }

        if ($order->status === OrderStatus::Pending) {
            $order->update(['status' => OrderStatus::InProgress]);
        }

        $event = ScanEvent::create([
            'order_id' => $order->id,
            'order_step_id' => $currentStep?->id,
            'workstation_id' => $workstation->id,
            'user_id' => $user->id,
            'event_type' => ScanEventType::Start,
            'scanned_at' => Carbon::now(),
            'notes' => $notes,
        ]);

        OrderLocationUpdated::dispatch($order->fresh());

        return $event;
    }

    public function completeWork(Order $order, Workstation $workstation, User $user, ?string $notes = null): ScanEvent
    {
        $currentStep = $order->currentStep();
        $duration = $this->calculateDuration($order, $workstation);

        $event = ScanEvent::create([
            'order_id' => $order->id,
            'order_step_id' => $currentStep?->id,
            'workstation_id' => $workstation->id,
            'user_id' => $user->id,
            'event_type' => ScanEventType::Complete,
            'scanned_at' => Carbon::now(),
            'duration_seconds' => $duration,
            'notes' => $notes,
        ]);

        if ($currentStep !== null) {
            $currentStep->update(['status' => StepStatus::Done]);
        }

        $this->checkOrderCompletion($order);

        OrderLocationUpdated::dispatch($order->fresh());

        return $event;
    }

    public function pauseWork(Order $order, Workstation $workstation, User $user, ?string $notes = null): ScanEvent
    {
        $currentStep = $order->currentStep();
        $duration = $this->calculateDuration($order, $workstation);

        $event = ScanEvent::create([
            'order_id' => $order->id,
            'order_step_id' => $currentStep?->id,
            'workstation_id' => $workstation->id,
            'user_id' => $user->id,
            'event_type' => ScanEventType::Pause,
            'scanned_at' => Carbon::now(),
            'duration_seconds' => $duration,
            'notes' => $notes,
        ]);

        OrderLocationUpdated::dispatch($order->fresh());

        return $event;
    }

    public function transferToWaiting(Order $order, Workstation $waitingArea, User $user, ?string $notes = null): ScanEvent
    {
        $currentStep = $order->currentStep();

        $event = ScanEvent::create([
            'order_id' => $order->id,
            'order_step_id' => $currentStep?->id,
            'workstation_id' => $waitingArea->id,
            'user_id' => $user->id,
            'event_type' => ScanEventType::TransferToWaiting,
            'scanned_at' => Carbon::now(),
            'notes' => $notes,
        ]);

        OrderLocationUpdated::dispatch($order->fresh());

        return $event;
    }

    private function validateNotActiveElsewhere(Order $order, Workstation $targetWorkstation): void
    {
        /** @var ScanEvent|null $lastEvent */
        $lastEvent = $order->scanEvents()
            ->whereIn('event_type', [ScanEventType::Start])
            ->first();

        if ($lastEvent === null) {
            return;
        }

        $hasBeenCompleted = $order->scanEvents()
            ->where('scanned_at', '>', $lastEvent->scanned_at)
            ->whereIn('event_type', [ScanEventType::Complete, ScanEventType::Pause, ScanEventType::TransferToWaiting])
            ->exists();

        if (! $hasBeenCompleted && $lastEvent->workstation_id !== $targetWorkstation->id) {
            /** @var Workstation|null $activeStation */
            $activeStation = $lastEvent->workstation;
            $stationName = $activeStation !== null ? $activeStation->name : 'unknown';
            throw new \RuntimeException(
                "Order #{$order->id} is currently active at workstation '{$stationName}'. ".
                'Complete or pause it there first.'
            );
        }
    }

    private function calculateDuration(Order $order, Workstation $workstation): ?int
    {
        /** @var ScanEvent|null $lastStart */
        $lastStart = $order->scanEvents()
            ->where('workstation_id', $workstation->id)
            ->where('event_type', ScanEventType::Start)
            ->orderByDesc('scanned_at')
            ->first();

        if ($lastStart === null) {
            return null;
        }

        return max(0, (int) abs(Carbon::now()->diffInSeconds($lastStart->scanned_at)));
    }

    private function checkOrderCompletion(Order $order): void
    {
        $allDone = $order->steps()
            ->whereNotIn('status', [StepStatus::Done, StepStatus::Skipped])
            ->doesntExist();

        if ($allDone) {
            $order->update([
                'status' => OrderStatus::Completed,
                'completed_at' => Carbon::now(),
            ]);
        }
    }
}
