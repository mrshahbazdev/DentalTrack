<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type_id',
        'sort_order',
        'step_name',
        'expected_minutes',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'expected_minutes' => 'integer',
    ];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }
}
