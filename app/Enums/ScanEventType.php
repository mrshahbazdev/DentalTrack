<?php

namespace App\Enums;

enum ScanEventType: string
{
    case Start = 'start';
    case Complete = 'complete';
    case Pause = 'pause';
    case TransferToWaiting = 'transfer_to_waiting';

    public function label(): string
    {
        return match ($this) {
            self::Start => 'Start',
            self::Complete => 'Complete',
            self::Pause => 'Pause',
            self::TransferToWaiting => 'Transfer to Waiting',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Start => 'info',
            self::Complete => 'success',
            self::Pause => 'warning',
            self::TransferToWaiting => 'gray',
        };
    }
}
