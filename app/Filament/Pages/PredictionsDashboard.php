<?php

namespace App\Filament\Pages;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Prediction;
use App\Models\ProductType;
use App\Services\PredictionService;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PredictionsDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'Prognosen';

    protected static ?string $navigationGroup = 'Analysen';

    protected static ?int $navigationSort = 14;

    protected static string $view = 'filament.pages.predictions-dashboard';

    /** @return array{avg_accuracy: float, total_predictions: int, recent_accuracy: float, by_version: array<string, array{accuracy: float, count: int}>} */
    public function getAccuracyStats(): array
    {
        return app(PredictionService::class)->getAccuracyStats();
    }

    /**
     * @return array<int, array{type: string, message: string, priority: string, data: array<string, mixed>}>
     */
    public function getSmartSuggestions(): array
    {
        return app(PredictionService::class)->getSmartSuggestions();
    }

    /** @return array<int, array{day: string, accuracy: float, count: int}> */
    public function getAccuracyTrend(): array
    {
        $rows = DB::table('predictions')
            ->whereNotNull('actual_minutes')
            ->whereNotNull('accuracy_pct')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('AVG(accuracy_pct) as avg_accuracy'),
                DB::raw('COUNT(*) as cnt')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $results = [];
        foreach ($rows as $row) {
            $results[] = [
                'day' => Carbon::parse($row->day)->format('M d'),
                'accuracy' => round((float) $row->avg_accuracy, 1),
                'count' => (int) $row->cnt,
            ];
        }

        return $results;
    }

    /** @return array<int, array{id: int, order_id: int, predicted: int, actual: int|null, accuracy: float|null, version: string, created: string}> */
    public function getRecentPredictions(): array
    {
        $predictions = Prediction::with('order')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $results = [];
        foreach ($predictions as $pred) {
            $results[] = [
                'id' => $pred->id,
                'order_id' => $pred->order_id,
                'predicted' => $pred->predicted_minutes,
                'actual' => $pred->actual_minutes,
                'accuracy' => $pred->accuracy_pct !== null ? (float) $pred->accuracy_pct : null,
                'version' => $pred->model_version,
                'created' => $pred->created_at !== null ? $pred->created_at->diffForHumans() : '',
            ];
        }

        return $results;
    }

    /** @return array<int, array{id: int, patient_ref: string, product: string, predicted_at: string|null, status: string}> */
    public function getActiveOrderPredictions(): array
    {
        $orders = Order::whereIn('status', [OrderStatus::InProgress, OrderStatus::Pending])
            ->whereNotNull('predicted_completion_at')
            ->with('productType')
            ->orderBy('predicted_completion_at')
            ->limit(15)
            ->get();

        $results = [];
        foreach ($orders as $order) {
            $pt = $order->productType;
            $results[] = [
                'id' => $order->id,
                'patient_ref' => $order->patient_ref ?? '',
                'product' => $pt instanceof ProductType ? $pt->name : 'Unknown',
                'predicted_at' => $order->predicted_completion_at !== null
                    ? $order->predicted_completion_at->format('M d, H:i')
                    : null,
                'status' => $order->status->value,
            ];
        }

        return $results;
    }
}
