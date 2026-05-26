<?php

namespace App\Models;

use App\Enums\WorkstationType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Workstation extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_id',
        'name',
        'qr_code',
        'type',
        'is_active',
    ];

    protected $casts = [
        'type' => WorkstationType::class,
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Workstation $workstation) {
            if (empty($workstation->qr_code)) {
                $workstation->qr_code = 'WS-'.Str::ulid();
            }
        });
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function scanEvents(): HasMany
    {
        return $this->hasMany(ScanEvent::class);
    }

    public function qrUrl(): string
    {
        return url("/scan/{$this->qr_code}");
    }
}
