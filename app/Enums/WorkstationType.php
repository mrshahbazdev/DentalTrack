<?php

namespace App\Enums;

enum WorkstationType: string
{
    case Station = 'station';
    case WaitingArea = 'waiting_area';

    public function label(): string
    {
        return match ($this) {
            self::Station => 'Station',
            self::WaitingArea => 'Waiting Area',
        };
    }
}
