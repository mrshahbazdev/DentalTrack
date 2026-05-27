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
            self::MaterialDefect => 'Material Defect',
            self::TechniqueError => 'Technique Error',
            self::EquipmentIssue => 'Equipment Issue',
            self::DesignError => 'Design Error',
            self::Other => 'Other',
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
