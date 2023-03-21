<?php

declare(strict_types=1);

namespace App\Enums;

enum BatteryCondition: string
{
    case HIGH = 'High';
    case LOW = 'Low';
    case EMPTY = 'Empty';
    case UNKNOWN = 'Unknown';

    public static function toArray(): array
    {
        $conditions = [];

        foreach (BatteryCondition::cases() as $value) {
            $conditions[$value->name] = $value->value;
        }

        return $conditions;
    }
}
