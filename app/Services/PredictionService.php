<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Prediction;
use App\Models\ProductType;
use App\Models\ScanEvent;
use Illuminate\Support\Carbon;

class PredictionService
{
    public function predictCompletion(Order $order): ?Prediction
    {
        /** @var ProductType $productType */
        $productType = $order->productType;
        $templates = $productType->processTemplates;

        if ($templates->isEmpty()) {
            return null;
        }

        $totalExpectedMinutes = $templates->sum('expected_minutes');
        if ($totalExpectedMinutes === 0) {
            return null;
        }

        $completedSteps = $order->steps()->where('status', 'done')->count();
        $totalSteps = $order->steps()->count();

        if ($totalSteps === 0) {
            return null;
        }

        $historicalAvg = $this->getHistoricalAverage($productType->id);

        if ($historicalAvg !== null) {
            $remainingRatio = max(0, ($totalSteps - $completedSteps) / $totalSteps);
            $predictedMinutes = (int) round($historicalAvg * $remainingRatio);
        } else {
            $remainingExpected = $templates
                ->skip($completedSteps)
                ->sum('expected_minutes');
            $predictedMinutes = (int) $remainingExpected;
        }

        $prediction = Prediction::create([
            'order_id' => $order->id,
            'model_version' => 'v1-weighted-avg',
            'predicted_minutes' => $predictedMinutes,
        ]);

        $order->update([
            'predicted_completion_at' => Carbon::now()->addMinutes($predictedMinutes),
        ]);

        return $prediction;
    }

    private function getHistoricalAverage(int $productTypeId): ?float
    {
        $completedOrders = Order::where('product_type_id', $productTypeId)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->limit(100)
            ->get();

        if ($completedOrders->count() < 5) {
            return null;
        }

        $totalMinutes = $completedOrders->sum(function (Order $order) {
            $firstScan = ScanEvent::where('order_id', $order->id)
                ->orderBy('scanned_at')
                ->value('scanned_at');

            if ($firstScan === null || $order->completed_at === null) {
                return 0;
            }

            return Carbon::parse($firstScan)->diffInMinutes($order->completed_at);
        });

        return $totalMinutes / $completedOrders->count();
    }
}
