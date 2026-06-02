<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case OnHold = 'on_hold';

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('app.status.pending'),
            self::InProgress => __('app.status.in_progress'),
            self::Completed => __('app.status.completed'),
            self::Cancelled => __('app.status.cancelled'),
            self::OnHold => __('app.status.on_hold'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::InProgress => 'info',
            self::Completed => 'success',
            self::Cancelled => 'danger',
            self::OnHold => 'warning',
        };
    }
}
