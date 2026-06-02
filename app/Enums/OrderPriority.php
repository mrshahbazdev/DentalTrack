<?php

namespace App\Enums;

enum OrderPriority: string
{
    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';
    case Urgent = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::Low => __('app.priority.low'),
            self::Normal => __('app.priority.normal'),
            self::High => __('app.priority.high'),
            self::Urgent => __('app.priority.urgent'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Low => 'gray',
            self::Normal => 'info',
            self::High => 'warning',
            self::Urgent => 'danger',
        };
    }
}
