<?php

declare(strict_types=1);

namespace App\Models\Traits;

use function auth;

trait HasUser
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $record): void {
            if (empty($record->user_id) && auth()->check()) {
                /** @var int<0, max> $userId */
                $userId = auth()->id();
                $record->user_id = $userId;
            }
        });
    }
}
