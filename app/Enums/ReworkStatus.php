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
            self::Pending => 'Pending',
            self::InRework => 'In Rework',
            self::Resolved => 'Resolved',
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
