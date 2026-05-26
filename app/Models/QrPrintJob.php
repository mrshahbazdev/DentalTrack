<?php

namespace App\Models;

use App\Enums\QrPrintFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class QrPrintJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'printable_type',
        'printable_id',
        'format',
        'printed_at',
    ];

    protected $casts = [
        'format' => QrPrintFormat::class,
        'printed_at' => 'datetime',
    ];

    public function printable(): MorphTo
    {
        return $this->morphTo();
    }
}
