<?php

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\PredictionService;

class OrderObserver
{
    public function __construct(
        private readonly PredictionService $predictionService,
    ) {}

    public function created(Order $order): void
    {
        if ($order->steps()->count() > 0) {
            $this->predictionService->predictCompletion($order);
        }
    }

    public function updated(Order $order): void
    {
        if ($order->wasChanged('status') && $order->status === OrderStatus::Completed) {
            $this->predictionService->updateAccuracy($order);
        }
    }
}
