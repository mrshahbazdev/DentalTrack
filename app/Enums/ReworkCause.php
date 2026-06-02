<?php

namespace App\Enums;

enum ReworkCause: string
{
    case MaterialDefect = 'material_defect';
    case TechniqueError = 'technique_error';
    case EquipmentIssue = 'equipment_issue';
    case DesignError = 'design_error';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::MaterialDefect => __('app.rework.material_defect'),
            self::TechniqueError => __('app.rework.technique_error'),
            self::EquipmentIssue => __('app.rework.equipment_issue'),
            self::DesignError => __('app.rework.design_error'),
            self::Other => __('app.rework.other'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::MaterialDefect => 'warning',
            self::TechniqueError => 'danger',
            self::EquipmentIssue => 'info',
            self::DesignError => 'primary',
            self::Other => 'gray',
        };
    }
}
