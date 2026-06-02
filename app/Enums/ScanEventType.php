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
            self::Start => __('app.scan.start'),
            self::Complete => __('app.scan.complete'),
            self::Pause => __('app.scan.pause'),
            self::TransferToWaiting => __('app.scan.transfer_to_waiting'),
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
