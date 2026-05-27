<?php

namespace App\Models;

use App\Enums\ReworkCause;
use App\Enums\ReworkStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReworkEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_step_id',
        'flagged_by',
        'original_technician',
        'cause',
        'description',
        'status',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'cause' => ReworkCause::class,
        'status' => ReworkStatus::class,
        'resolved_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderStep(): BelongsTo
    {
        return $this->belongsTo(OrderStep::class);
    }

    public function flaggedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'flagged_by');
    }

    public function originalTechnician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'original_technician');
    }

    public function resolvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
