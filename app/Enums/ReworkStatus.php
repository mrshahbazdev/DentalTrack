<?php

namespace App\Enums;

enum ReworkStatus: string
{
    case Pending = 'pending';
    case InRework = 'in_rework';
    case Resolved = 'resolved';

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('app.rework.pending'),
            self::InRework => __('app.rework.in_rework'),
            self::Resolved => __('app.rework.resolved'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::InRework => 'info',
            self::Resolved => 'success',
        };
    }
}
