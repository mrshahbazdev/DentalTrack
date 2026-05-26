<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'model_version',
        'predicted_minutes',
        'actual_minutes',
        'accuracy_pct',
    ];

    protected $casts = [
        'predicted_minutes' => 'integer',
        'actual_minutes' => 'integer',
        'accuracy_pct' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
