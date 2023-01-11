<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\UserReport;
use Illuminate\Database\Eloquent\Collection;

trait GetsReportRecipients
{
    public function getRecipients(): Collection
    {
        return UserReport::query()->where($this->key, '=', true)->get();
    }
}
