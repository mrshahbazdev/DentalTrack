<?php

namespace App\Models;

use App\Enums\StepStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'process_template_id',
        'sort_order',
        'step_name',
        'status',
        'assigned_to',
    ];

    protected $casts = [
        'status' => StepStatus::class,
        'sort_order' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function processTemplate(): BelongsTo
    {
        return $this->belongsTo(ProcessTemplate::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scanEvents(): HasMany
    {
        return $this->hasMany(ScanEvent::class);
    }

    public function totalDurationSeconds(): int
    {
        return (int) $this->scanEvents()->sum('duration_seconds');
    }
}
