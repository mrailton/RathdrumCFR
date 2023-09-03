<?php

namespace App\Models\Traits;

use function auth;

trait HasUser
{
    protected static function boot(): void
    {
        static::creating(function (self $record) {
            $record->user_id = auth()->id();
        });

        parent::boot();
    }
}
