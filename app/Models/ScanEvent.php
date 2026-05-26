<?php

namespace App\Models;

use App\Enums\ScanEventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScanEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_step_id',
        'workstation_id',
        'user_id',
        'event_type',
        'scanned_at',
        'duration_seconds',
        'notes',
    ];

    protected $casts = [
        'event_type' => ScanEventType::class,
        'scanned_at' => 'datetime',
        'duration_seconds' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderStep(): BelongsTo
    {
        return $this->belongsTo(OrderStep::class);
    }

    public function workstation(): BelongsTo
    {
        return $this->belongsTo(Workstation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function formattedDuration(): string
    {
        if ($this->duration_seconds === null) {
            return '-';
        }

        $hours = intdiv($this->duration_seconds, 3600);
        $minutes = intdiv($this->duration_seconds % 3600, 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $minutes);
        }

        if ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $seconds);
        }

        return sprintf('%ds', $seconds);
    }
}
