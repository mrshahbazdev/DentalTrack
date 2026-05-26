<?php

namespace App\Models;

use App\Enums\OrderPriority;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'lab_id',
        'product_type_id',
        'patient_ref',
        'doctor_name',
        'qr_code',
        'priority',
        'due_date',
        'status',
        'notes',
        'predicted_completion_at',
        'completed_at',
    ];

    protected $casts = [
        'priority' => OrderPriority::class,
        'status' => OrderStatus::class,
        'due_date' => 'date',
        'predicted_completion_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->qr_code)) {
                $order->qr_code = 'ORD-'.Str::ulid();
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(OrderStep::class)->orderBy('sort_order');
    }

    public function scanEvents(): HasMany
    {
        return $this->hasMany(ScanEvent::class)->orderByDesc('scanned_at');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function currentStep(): ?OrderStep
    {
        /** @var OrderStep|null */
        return $this->steps()
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('sort_order')
            ->first();
    }

    public function latestScanEvent(): ?ScanEvent
    {
        /** @var ScanEvent|null */
        return $this->scanEvents()->first();
    }

    public function qrUrl(): string
    {
        return url("/scan/{$this->qr_code}");
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && ! in_array($this->status, [OrderStatus::Completed, OrderStatus::Cancelled]);
    }

    public function completedStepsCount(): int
    {
        return $this->steps()->where('status', 'done')->count();
    }

    public function totalStepsCount(): int
    {
        return $this->steps()->count();
    }

    public function progressPercentage(): float
    {
        $total = $this->totalStepsCount();
        if ($total === 0) {
            return 0;
        }

        return round(($this->completedStepsCount() / $total) * 100, 1);
    }
}
