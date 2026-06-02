<?php

namespace App\Enums;

enum WorkstationType: string
{
    case Station = 'station';
    case WaitingArea = 'waiting_area';

    public function label(): string
    {
        return match ($this) {
            self::Station => __('app.workstation_type.station'),
            self::WaitingArea => __('app.workstation_type.waiting_area'),
        };
    }
}
