<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\DefibInspected;
use App\Models\DefibInspection;

class DefibInspectionObserver
{
    public function created(DefibInspection $inspection): void
    {
        $inspection->defib()->update([
            'pads_expire_at' => $inspection->pads_expire_at,
            'battery_expires_at' => $inspection->battery_expires_at,
            'last_inspected_at' => $inspection->inspected_at,
            'last_inspected_by' => $inspection->member?->name,
        ]);

        $defib = $inspection->defib;
        if (null !== $defib) {
            DefibInspected::dispatch($defib, $inspection);
        }
    }
}
