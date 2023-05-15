<?php

namespace App\Enums;

enum WasteDisposalMethods: string
{
    case NAS_CREW = 'NAS Crew';
    case DFB_CREW = 'DFB Crew';
    case NAS_STATION = 'NAS Station';
    case HOSPITAL = 'Hospital';
    case RESPONDER = 'Responder';
    case OTHER = 'Other';

    public static function toArray(): array
    {
        $methods = [];

        foreach (WasteDisposalMethods::cases() as $value) {
            $methods[$value->name] = $value->value;
        }

        return $methods;
    }
}
