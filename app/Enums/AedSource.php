<?php

namespace App\Enums;

enum AedSource: string
{
    case PAD = 'PAD';
    case CFR = 'CFR';
    case NAS = 'NAS';
    case FIRE = 'Fire';
    case GARDA = 'Garda';
    case OTHER = 'Other';

    public static function toArray(): array
    {
        $sources = [];

        foreach (AedSource::cases() as $value) {
            $sources[$value->name] = $value->value;
        }

        return $sources;
    }
}
