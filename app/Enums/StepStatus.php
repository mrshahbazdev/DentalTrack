<?php

namespace App\Enums;

enum StepStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Done = 'done';
    case Skipped = 'skipped';

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('app.step_status.pending'),
            self::InProgress => __('app.step_status.in_progress'),
            self::Done => __('app.step_status.done'),
            self::Skipped => __('app.step_status.skipped'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::InProgress => 'info',
            self::Done => 'success',
            self::Skipped => 'warning',
        };
    }
}
